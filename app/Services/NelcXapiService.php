<?php

namespace App\Services;

use App\Jobs\SendNelcXapiStatementJob;
use App\Models\Certificate;
use App\Models\File;
use App\Models\NelcXapiStatement;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Session;
use App\Models\TextLesson;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarReview;
use App\User;
use Illuminate\Support\Facades\Log;

class NelcXapiService
{
    /**
     * Check if the NELC xAPI integration is enabled.
     */
    public function isEnabled(): bool
    {
        return (bool) config('lrs-nelc-xapi.enabled', false);
    }

    /**
     * Get the learner's National ID from user_metas.
     */
    public function getNationalId(User $user): ?string
    {
        $meta = $user->userMetas->where('name', 'certificate_additional')->first();

        if (empty($meta) || empty($meta->value)) {
            return null;
        }

        $nationalId = trim($meta->value);

        // Validate: 10 digits starting with 1, 2, or 4
        if (!preg_match('/^[124]\d{9}$/', $nationalId)) {
            return null;
        }

        return $nationalId;
    }

    /**
     * Get instructor info from a webinar/course.
     */
    public function getInstructor(Webinar $course): array
    {
        $teacher = $course->teacher;

        if (empty($teacher)) {
            $teacher = $course->creator;
        }

        return [
            'name' => $teacher ? $teacher->full_name : 'Unknown',
            'email' => $teacher ? ($teacher->email ?? $teacher->full_name . '@platform.com') : 'unknown@platform.com',
        ];
    }

    /**
     * Build the xAPI object ID URL for a course.
     */
    public function getCourseUrl(Webinar $course): string
    {
        return url('/course/' . $course->slug);
    }

    /**
     * Get clean course description (strip HTML tags).
     */
    public function getCourseDescription(Webinar $course): string
    {
        $description = $course->description ?? '';
        $description = strip_tags($description);
        $description = trim($description);

        return mb_substr($description, 0, 1000);
    }

    /**
     * Get course title.
     */
    public function getCourseTitle(Webinar $course): string
    {
        return $course->title ?? 'Untitled Course';
    }

    /**
     * Build the student email in the format NELC expects.
     */
    public function getStudentEmail(User $user): string
    {
        return $user->email ?? ($user->id . '@platform.local');
    }

    /**
     * Check duplicate and log statement.
     * Returns true if duplicate exists (should skip sending).
     */
    public function checkAndPreventDuplicate(int $userId, string $verb, string $objectId): bool
    {
        return NelcXapiStatement::isDuplicate($userId, $verb, $objectId);
    }

    /**
     * Log a sent xAPI statement to the database.
     */
    public function logStatement(
        int $userId,
        ?int $webinarId,
        string $verb,
        string $objectType,
        string $objectId,
        ?int $responseStatus = null,
        ?string $responseBody = null,
        ?array $payload = null
    ): NelcXapiStatement {
        return NelcXapiStatement::create([
            'user_id' => $userId,
            'webinar_id' => $webinarId,
            'verb' => $verb,
            'object_type' => $objectType,
            'object_id' => $objectId,
            'response_status' => $responseStatus,
            'response_body' => $responseBody,
            'payload' => $payload,
        ]);
    }

    // =========================================================================
    // xAPI Statement Methods
    // =========================================================================

    /**
     * Send "registered" statement when a student enrolls in a course.
     */
    public function sendRegistered(User $user, Webinar $course): void
    {
        $this->dispatch('registered', NelcXapiStatement::TYPE_COURSE, $user, $course, function () use ($user, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            return [
                'method' => 'Registered',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        });
    }

    /**
     * Send "initialized" statement when a student first accesses a course.
     */
    public function sendInitialized(User $user, Webinar $course): void
    {
        $this->dispatch('initialized', NelcXapiStatement::TYPE_COURSE, $user, $course, function () use ($user, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            return [
                'method' => 'Initialized',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        });
    }

    /**
     * Send "watched" statement when a student completes watching a video.
     */
    public function sendWatched(User $user, File $file, Webinar $course): void
    {
        $objectId = $this->getCourseUrl($course) . '/video/' . $file->id;

        $this->dispatch('watched', NelcXapiStatement::TYPE_VIDEO, $user, $course, function () use ($user, $file, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            // Calculate duration in ISO 8601 format
            $durationSeconds = $file->duration ?? 0;
            $duration = $this->secondsToISO8601($durationSeconds);

            return [
                'method' => 'Watched',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course) . '/video/' . $file->id,
                    ($file->title ?? 'Video') . ' - ' . $this->getCourseTitle($course),
                    $file->description ?? '',
                    true, // completion
                    $duration,
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        }, $objectId);
    }

    /**
     * Send "completed lesson" statement.
     */
    public function sendCompletedLesson(User $user, TextLesson $lesson, Webinar $course): void
    {
        $objectId = $this->getCourseUrl($course) . '/lesson/' . $lesson->id;

        $this->dispatch('completed', NelcXapiStatement::TYPE_LESSON, $user, $course, function () use ($user, $lesson, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            return [
                'method' => 'CompletedLesson',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course) . '/lesson/' . $lesson->id,
                    ($lesson->title ?? 'Lesson') . ' - ' . $this->getCourseTitle($course),
                    $lesson->description ?? '',
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        }, $objectId);
    }

    /**
     * Send "attended" statement for virtual classroom sessions.
     */
    public function sendAttended(User $user, Session $session, Webinar $course): void
    {
        $objectId = $this->getCourseUrl($course) . '/session/' . $session->id;

        $this->dispatch('attended', NelcXapiStatement::TYPE_VIRTUAL_CLASSROOM, $user, $course, function () use ($user, $session, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            $durationSeconds = $session->duration ?? 0;
            $duration = $this->secondsToISO8601($durationSeconds * 60); // sessions store duration in minutes

            return [
                'method' => 'CompletedLesson', // attended uses the same structure as completed lesson
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course) . '/session/' . $session->id,
                    ($session->title ?? 'Session') . ' - ' . $this->getCourseTitle($course),
                    $session->description ?? '',
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        }, $objectId);
    }

    /**
     * Send "completed unit/module" statement.
     */
    public function sendCompletedUnit(User $user, WebinarChapter $chapter, Webinar $course): void
    {
        $objectId = $this->getCourseUrl($course) . '/module/' . $chapter->id;

        $this->dispatch('completed', NelcXapiStatement::TYPE_MODULE, $user, $course, function () use ($user, $chapter, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            return [
                'method' => 'CompletedUnit',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course) . '/module/' . $chapter->id,
                    ($chapter->title ?? 'Module') . ' - ' . $this->getCourseTitle($course),
                    '',
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        }, $objectId);
    }

    /**
     * Send "attempted" statement when a quiz is submitted.
     */
    public function sendAttempted(User $user, Quiz $quiz, QuizzesResult $result, Webinar $course): void
    {
        // For attempted, we allow multiple attempts (don't deduplicate by object_id alone)
        $attemptCount = QuizzesResult::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->count();

        $objectId = $this->getCourseUrl($course) . '/quiz/' . $quiz->id . '/attempt/' . $attemptCount;

        $this->dispatch('attempted', NelcXapiStatement::TYPE_QUIZ, $user, $course, function () use ($user, $quiz, $result, $course, $attemptCount) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            $totalMark = $quiz->total_mark > 0 ? $quiz->total_mark : 100;
            $userGrade = $result->user_grade ?? 0;
            $passMark = $quiz->pass_mark ?? 0;
            $scaled = $totalMark > 0 ? round($userGrade / $totalMark, 2) : 0;
            $success = $result->status === QuizzesResult::$passed;
            $completion = in_array($result->status, [QuizzesResult::$passed, QuizzesResult::$failed]);

            return [
                'method' => 'Attempted',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course) . '/quiz/' . $quiz->id,
                    ($quiz->title ?? 'Quiz') . ' - ' . $this->getCourseTitle($course),
                    $quiz->title ?? 'Quiz',
                    $attemptCount,
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                    $scaled,
                    $userGrade,
                    0,
                    $totalMark,
                    $completion,
                    $success,
                ],
            ];
        }, $objectId);
    }

    /**
     * Send "progressed" statement with current progress percentage.
     */
    public function sendProgressed(User $user, Webinar $course, float $progress): void
    {
        // For progress, we update the value each time (allow overwrite)
        // Use a fixed object_id so we can track the latest progress
        $objectId = $this->getCourseUrl($course);
        $scaled = round($progress / 100, 2);
        $completion = $progress >= 100;

        // Don't deduplicate progress - always send latest value
        // But we do need to log it
        try {
            if (!$this->isEnabled()) {
                return;
            }

            $nationalId = $this->getNationalId($user);
            if (empty($nationalId)) {
                return;
            }

            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            $payload = [
                'method' => 'Progressed',
                'args' => [
                    $nationalId,
                    $email,
                    $objectId,
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                    $scaled,
                    $completion,
                ],
            ];

            SendNelcXapiStatementJob::dispatch(
                $user->id,
                $course->id,
                'progressed',
                NelcXapiStatement::TYPE_COURSE,
                $objectId . '/progress/' . $scaled,
                $payload
            );
        } catch (\Exception $e) {
            Log::error('NELC xAPI sendProgressed error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'webinar_id' => $course->id,
            ]);
        }
    }

    /**
     * Send "completed course" statement.
     */
    public function sendCompletedCourse(User $user, Webinar $course): void
    {
        $this->dispatch('completed', NelcXapiStatement::TYPE_COURSE, $user, $course, function () use ($user, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            return [
                'method' => 'CompletedCourse',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                ],
            ];
        });
    }

    /**
     * Send "rated" statement when a student rates a course.
     */
    public function sendRated(User $user, Webinar $course, WebinarReview $review): void
    {
        $this->dispatch('rated', NelcXapiStatement::TYPE_COURSE, $user, $course, function () use ($user, $course, $review) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);
            $instructor = $this->getInstructor($course);

            $raw = $review->rates ?? 0;
            $scaled = $raw > 0 ? round($raw / 5, 2) : 0;
            $comment = $review->description ?? '';

            return [
                'method' => 'Rated',
                'args' => [
                    $nationalId,
                    $email,
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                    $instructor['name'],
                    $instructor['email'],
                    $scaled,
                    $raw,
                    $comment,
                ],
            ];
        });
    }

    /**
     * Send "earned" statement when a certificate is awarded.
     */
    public function sendEarned(User $user, Certificate $certificate, Webinar $course): void
    {
        $objectId = url('/certificate/class/' . $certificate->id);

        $this->dispatch('earned', NelcXapiStatement::TYPE_CERTIFICATE, $user, $course, function () use ($user, $certificate, $course) {
            $nationalId = $this->getNationalId($user);
            $email = $this->getStudentEmail($user);

            return [
                'method' => 'Earned',
                'args' => [
                    $nationalId,
                    $email,
                    url('/certificate/class/' . $certificate->id),
                    $this->getCourseTitle($course) . ' Certificate',
                    $this->getCourseUrl($course),
                    $this->getCourseTitle($course),
                    $this->getCourseDescription($course),
                ],
            ];
        }, $objectId);
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Common dispatch logic for all xAPI statements.
     */
    protected function dispatch(
        string $verb,
        string $objectType,
        User $user,
        Webinar $course,
        callable $payloadBuilder,
        ?string $objectId = null
    ): void {
        try {
            if (!$this->isEnabled()) {
                return;
            }

            $nationalId = $this->getNationalId($user);
            if (empty($nationalId)) {
                Log::info('NELC xAPI: Skipping statement - no national ID', [
                    'user_id' => $user->id,
                    'verb' => $verb,
                ]);
                return;
            }

            // Build the object ID if not provided
            if (empty($objectId)) {
                $objectId = $this->getCourseUrl($course);
            }

            // Check for duplicate
            if ($this->checkAndPreventDuplicate($user->id, $verb, $objectId)) {
                Log::info('NELC xAPI: Skipping duplicate statement', [
                    'user_id' => $user->id,
                    'verb' => $verb,
                    'object_id' => $objectId,
                ]);
                return;
            }

            $payload = $payloadBuilder();

            SendNelcXapiStatementJob::dispatch(
                $user->id,
                $course->id,
                $verb,
                $objectType,
                $objectId,
                $payload
            );
        } catch (\Exception $e) {
            Log::error('NELC xAPI dispatch error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'webinar_id' => $course->id,
                'verb' => $verb,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Convert seconds to ISO 8601 duration format.
     */
    public function secondsToISO8601(int $seconds): string
    {
        if ($seconds <= 0) {
            return 'PT0S';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        $duration = 'PT';
        if ($hours > 0) {
            $duration .= sprintf('%02dH', $hours);
        }
        if ($minutes > 0) {
            $duration .= sprintf('%02dM', $minutes);
        }
        if ($secs > 0) {
            $duration .= sprintf('%02dS', $secs);
        }

        return $duration;
    }
}
