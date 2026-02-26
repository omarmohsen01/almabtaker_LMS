<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Webinar;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NelcXapiService
{
    /**
     * Check if NELC integration is enabled and configured
     */
    protected function isEnabled(): bool
    {
        return config('nelc-xapi.enabled')
            && !empty(config('nelc-xapi.endpoint'))
            && !empty(config('nelc-xapi.key'))
            && !empty(config('nelc-xapi.secret'));
    }

    /**
     * Check if user has valid national_id for NELC
     */
    protected function isValidForNelc(User $user): bool
    {
        return !empty($user->national_id) && preg_match('/^[124]\d{9}$/', $user->national_id);
    }

    /**
     * Check if statement was already sent (prevent duplicates)
     */
    protected function alreadySent(int $userId, string $verb, string $objectType, ?int $objectId = null, ?int $courseId = null): bool
    {
        return DB::table('nelc_xapi_statements')
            ->where('user_id', $userId)
            ->where('verb', $verb)
            ->where('object_type', $objectType)
            ->where('object_id', $objectId)
            ->where('course_id', $courseId)
            ->exists();
    }

    /**
     * Record that a statement was sent
     */
    protected function recordStatement(int $userId, string $verb, string $objectType, ?int $objectId, ?int $courseId, ?array $response): void
    {
        try {
            DB::table('nelc_xapi_statements')->insert([
                'user_id' => $userId,
                'verb' => $verb,
                'object_type' => $objectType,
                'object_id' => $objectId,
                'course_id' => $courseId,
                'lrs_response_uuid' => $response['body'] ?? null,
                'status_code' => $response['status'] ?? null,
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('NELC: Failed to record statement', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send raw xAPI statement to NELC LRS
     */
    protected function sendStatement(array $statement): ?array
    {
        if (!$this->isEnabled()) {
            return null;
        }

        try {
            $response = Http::withBasicAuth(
                config('nelc-xapi.key'),
                config('nelc-xapi.secret')
            )
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Experience-API-Version' => '1.0.3',
                ])
                ->timeout(15)
                ->post(config('nelc-xapi.endpoint'), $statement);

            return [
                'status' => $response->status(),
                'message' => $response->successful() ? 'ok' : 'error',
                'body' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('NELC xAPI Send Error', ['error' => $e->getMessage()]);
            return ['status' => 0, 'message' => 'exception', 'body' => $e->getMessage()];
        }
    }

    // ─── Builders ────────────────────────────────────────────

    protected function buildActor(User $user): array
    {
        return [
            'mbox' => 'mailto:' . ($user->email ?? $user->national_id . '@almabtaker.com'),
            'name' => $user->national_id,
            'objectType' => 'Agent',
        ];
    }

    protected function buildPlatformExtension(): array
    {
        return [
            'https://nelc.gov.sa/extensions/platform' => [
                'name' => [
                    'ar-SA' => config('nelc-xapi.platform_in_arabic'),
                    'en-US' => config('nelc-xapi.platform_in_english'),
                ],
            ],
        ];
    }

    /**
     * Build course object FOR REGISTRATION ONLY (includes description).
     * FIX CTX001 + CRS008: Only registration statements should have course description.
     */
    protected function buildCourseObject(Webinar $course, bool $includeDescription = false): array
    {
        $definition = [
            'name' => ['en-US' => $course->title ?? 'Course'],
            'type' => 'https://w3id.org/xapi/cmi5/activitytype/course',
        ];

        // Only include description for registration statements (CTX001 + CRS008 fix)
        if ($includeDescription) {
            $desc = mb_substr(strip_tags($course->description ?? ''), 0, 500);
            if (!empty($desc)) {
                $definition['description'] = ['en-US' => $desc];
            }
        }

        return [
            'id' => config('nelc-xapi.lms_url') . '/course/' . $course->slug,
            'definition' => $definition,
            'objectType' => 'Activity',
        ];
    }

    protected function buildBaseContext(Webinar $course = null): array
    {
        $context = [
            'platform' => config('nelc-xapi.platform_code'),
            'language' => config('nelc-xapi.language', 'ar-SA'),
            'extensions' => $this->buildPlatformExtension(),
        ];

        if ($course) {
            $teacher = $course->teacher;
            $context['instructor'] = [
                'name' => $teacher->full_name ?? 'Instructor',
                'mbox' => 'mailto:' . ($teacher->email ?? 'instructor@almabtaker.com'),
            ];
        }

        return $context;
    }

    /**
     * Build context with parent course activity (no description in parent).
     * FIX CTX001: Parent course object should not contain description.
     */
    protected function buildContextWithParent(Webinar $course): array
    {
        $context = $this->buildBaseContext($course);
        $context['contextActivities'] = [
            'parent' => [$this->buildCourseObject($course, false)],
        ];
        return $context;
    }

    /**
     * Convert seconds to ISO 8601 duration
     */
    public static function secondsToIso(int $seconds): string
    {
        if ($seconds <= 0) return 'PT00H00M00S';
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return sprintf('PT%02dH%02dM%02dS', $h, $m, $s);
    }

    /**
     * FIX SEQ001 + SEQ002: Ensure registered + initialized are sent before any other statement.
     * This auto-sends them if they haven't been sent yet for this user+course.
     */
    protected function ensureRegisteredAndInitialized(User $user, Webinar $course): void
    {
        try {
            if (!$this->alreadySent($user->id, 'registered', 'course', $course->id, $course->id)) {
                $this->sendRegistered($user, $course);
            }
            if (!$this->alreadySent($user->id, 'initialized', 'course', $course->id, $course->id)) {
                $this->sendInitialized($user, $course);
            }
        } catch (\Exception $e) {
            Log::warning('NELC: Failed to auto-send registered/initialized', ['error' => $e->getMessage()]);
        }
    }

    // ─── Statements ──────────────────────────────────────────

    /**
     * #1 — registered
     */
    public function sendRegistered(User $user, Webinar $course): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'registered', 'course', $course->id, $course->id)) return null;

        $context = $this->buildBaseContext($course);
        $context['extensions'] = array_merge($context['extensions'], [
            'https://nelc.gov.sa/extensions/lms_url' => config('nelc-xapi.lms_url'),
            'https://nelc.gov.sa/extensions/program_url' => config('nelc-xapi.lms_url') . '/course/' . $course->slug,
            'https://nelc.gov.sa/extensions/learner_full_name' => $user->full_name ?? '',
        ]);

        if (!empty($user->mobile)) {
            $context['extensions']['https://nelc.gov.sa/extensions/learner_mobile_no'] = $user->mobile;
        }

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/registered', 'display' => ['en-US' => 'registered']],
            'object' => $this->buildCourseObject($course, true), // Only registration includes description
            'context' => $context,
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: registered', ['user' => $user->id, 'course' => $course->id, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'registered', 'course', $course->id, $course->id, $response);
        return $response;
    }

    /**
     * #2 — initialized (no description in course object - CRS008 fix)
     */
    public function sendInitialized(User $user, Webinar $course): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'initialized', 'course', $course->id, $course->id)) return null;

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/initialized', 'display' => ['en-US' => 'initialized']],
            'object' => $this->buildCourseObject($course, false), // No description (CRS008)
            'context' => $this->buildBaseContext($course),
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: initialized', ['user' => $user->id, 'course' => $course->id, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'initialized', 'course', $course->id, $course->id, $response);
        return $response;
    }

    /**
     * #3 — watched (video)
     */
    public function sendWatched(User $user, Webinar $course, int $fileId, string $videoUrl, string $title, string $description, bool $completed, string $duration): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'watched', 'video', $fileId, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'https://w3id.org/xapi/acrossx/verbs/watched', 'display' => ['en-US' => 'watched']],
            'object' => [
                'id' => $videoUrl,
                'definition' => [
                    'name' => ['en-US' => $title],
                    'description' => ['en-US' => mb_substr(strip_tags($description), 0, 500)],
                    'type' => 'https://w3id.org/xapi/video/activity-type/video',
                ],
                'objectType' => 'Activity',
            ],
            'context' => $this->buildContextWithParent($course),
            'result' => [
                'completion' => $completed,
                'duration' => $duration,
            ],
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: watched', ['user' => $user->id, 'file' => $fileId, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'watched', 'video', $fileId, $course->id, $response);
        return $response;
    }

    /**
     * #4 — completed lesson
     */
    public function sendCompletedLesson(User $user, Webinar $course, int $lessonId, string $lessonUrl, string $title, string $description): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'completed', 'lesson', $lessonId, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed', 'display' => ['en-US' => 'completed']],
            'object' => [
                'id' => $lessonUrl,
                'definition' => [
                    'name' => ['en-US' => $title],
                    'description' => ['en-US' => mb_substr(strip_tags($description), 0, 500)],
                    'type' => 'http://adlnet.gov/expapi/activities/lesson',
                ],
                'objectType' => 'Activity',
            ],
            'context' => $this->buildContextWithParent($course),
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: completed lesson', ['user' => $user->id, 'lesson' => $lessonId, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'completed', 'lesson', $lessonId, $course->id, $response);
        return $response;
    }

    /**
     * #5 — attended (virtual classroom / session)
     */
    public function sendAttended(User $user, Webinar $course, int $sessionId, string $sessionUrl, string $title, string $description, string $duration): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'attended', 'session', $sessionId, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/attended', 'display' => ['en-US' => 'attended']],
            'object' => [
                'id' => $sessionUrl,
                'definition' => [
                    'name' => ['en-US' => $title],
                    'description' => ['en-US' => mb_substr(strip_tags($description), 0, 500)],
                    'type' => 'https://w3id.org/xapi/virtual-classroom/activity-types/virtual-classroom',
                ],
                'objectType' => 'Activity',
            ],
            'context' => $this->buildContextWithParent($course),
            'result' => [
                'completion' => true,
                'duration' => $duration,
            ],
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: attended', ['user' => $user->id, 'session' => $sessionId, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'attended', 'session', $sessionId, $course->id, $response);
        return $response;
    }

    /**
     * #6 — attempted (quiz)
     * FIX ASM002: Removed score.min to avoid "success is false but score.raw >= score.min"
     * FIX CRS006: Made description different from name
     */
    public function sendAttempted(User $user, Webinar $course, $quiz, float $scaledScore, float $rawScore, float $maxScore, bool $success, int $attemptNumber): ?array
    {
        if (!$this->isValidForNelc($user)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $quizUrl = config('nelc-xapi.lms_url') . '/course/' . $course->slug . '/quiz/' . $quiz->id;

        $context = $this->buildContextWithParent($course);
        $context['extensions'] = array_merge(
            $context['extensions'] ?? [],
            $this->buildPlatformExtension(),
            ['http://id.tincanapi.com/extension/attempt-id' => $attemptNumber]
        );

        // FIX CRS006: description must be different from name
        $quizName = $quiz->title ?? 'Quiz';
        $quizDescription = 'Assessment: ' . $quizName . ' - Attempt ' . $attemptNumber;

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/attempted', 'display' => ['en-US' => 'attempted']],
            'object' => [
                'id' => $quizUrl,
                'definition' => [
                    'name' => ['en-US' => $quizName],
                    'description' => ['en-US' => $quizDescription],
                    'type' => 'http://id.tincanapi.com/activitytype/unit-test',
                ],
                'objectType' => 'Activity',
            ],
            'context' => $context,
            'result' => [
                'score' => [
                    'scaled' => round($scaledScore, 2),
                    'raw' => $rawScore,
                    'min' => 0, // Minimum possible score (required by ASM001)
                    'max' => $maxScore,
                ],
                'success' => $success, // Must be correctly set based on passing criteria, not score.raw >= score.min
                'completion' => true,
            ],
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: attempted', ['user' => $user->id, 'quiz' => $quiz->id, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'attempted', 'quiz', $quiz->id, $course->id, $response);
        return $response;
    }

    /**
     * #7 — completed module (chapter)
     * FIX CRS006: Made description different from name
     */
    public function sendCompletedModule(User $user, Webinar $course, int $chapterId, string $chapterTitle): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'completed', 'module', $chapterId, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $moduleUrl = config('nelc-xapi.lms_url') . '/course/' . $course->slug . '/module/' . $chapterId;

        // FIX CRS006: description must be different from name
        $moduleDescription = 'Module: ' . $chapterTitle . ' in course ' . ($course->title ?? 'Course');

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed', 'display' => ['en-US' => 'completed']],
            'object' => [
                'id' => $moduleUrl,
                'definition' => [
                    'name' => ['en-US' => $chapterTitle],
                    'description' => ['en-US' => $moduleDescription],
                    'type' => 'http://adlnet.gov/expapi/activities/module',
                ],
                'objectType' => 'Activity',
            ],
            'context' => $this->buildContextWithParent($course),
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: completed module', ['user' => $user->id, 'chapter' => $chapterId, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'completed', 'module', $chapterId, $course->id, $response);
        return $response;
    }

    /**
     * #8 — progressed
     * FIX CRS008: No description in course object for non-registration statements
     * FIX SEQ003: Prevent duplicate progressed statements with same scaled score
     */
    public function sendProgressed(User $user, Webinar $course, float $progressPercent): ?array
    {
        if (!$this->isValidForNelc($user)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $scaled = round($progressPercent / 100, 2);
        if ($scaled > 1) $scaled = 1.0;
        if ($scaled < 0) $scaled = 0.0;

        // FIX SEQ003: Prevent duplicate progressed statements with same scaled score.
        // We store progress percentage (0-100) as object_id to detect duplicates.
        $progressInt = (int) round($scaled * 100);
        if ($this->alreadySent($user->id, 'progressed', 'course', $progressInt, $course->id)) {
            Log::info('NELC: progressed skipped (duplicate)', ['user' => $user->id, 'course' => $course->id, 'progress' => $progressInt]);
            return null;
        }

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/progressed', 'display' => ['en-US' => 'progressed']],
            'object' => $this->buildCourseObject($course, false), // No description (CRS008)
            'context' => $this->buildBaseContext($course),
            'result' => [
                'score' => ['scaled' => $scaled],
                'completion' => $scaled >= 1.0,
            ],
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: progressed', ['user' => $user->id, 'course' => $course->id, 'progress' => $progressPercent, 'status' => $response['status'] ?? null]);
        // Record with progress percentage as object_id for duplicate detection
        $this->recordStatement($user->id, 'progressed', 'course', $progressInt, $course->id, $response);
        return $response;
    }

    /**
     * #9 — completed course (no description in course object - CRS008 fix)
     */
    public function sendCompletedCourse(User $user, Webinar $course): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'completed', 'course', $course->id, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed', 'display' => ['en-US' => 'completed']],
            'object' => $this->buildCourseObject($course, false), // No description (CRS008)
            'context' => $this->buildBaseContext($course),
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: completed course', ['user' => $user->id, 'course' => $course->id, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'completed', 'course', $course->id, $course->id, $response);
        return $response;
    }

    /**
     * #10 — rated (no description in course object - CRS008 fix)
     */
    public function sendRated(User $user, Webinar $course, float $rawRating, string $reviewText = ''): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'rated', 'course', $course->id, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://id.tincanapi.com/verb/rated', 'display' => ['en-US' => 'rated']],
            'object' => $this->buildCourseObject($course, false), // No description (CRS008)
            'context' => $this->buildBaseContext($course),
            'result' => [
                'score' => [
                    'scaled' => round($rawRating / 5, 2),
                    'raw' => round($rawRating, 1),
                    'min' => 0,
                    'max' => 5,
                ],
                'response' => mb_substr($reviewText, 0, 500),
            ],
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: rated', ['user' => $user->id, 'course' => $course->id, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'rated', 'course', $course->id, $course->id, $response);
        return $response;
    }

    /**
     * #11 — earned (certificate) - parent course object without description
     */
    public function sendEarned(User $user, Webinar $course, Certificate $certificate): ?array
    {
        if (!$this->isValidForNelc($user)) return null;
        if ($this->alreadySent($user->id, 'earned', 'certificate', $certificate->id, $course->id)) return null;

        // FIX SEQ001/SEQ002: Ensure registration lifecycle statements exist
        $this->ensureRegisteredAndInitialized($user, $course);

        $certUrl = config('nelc-xapi.lms_url') . '/certificate_validation?certificate_id=' . $certificate->id;
        $certObjectUrl = config('nelc-xapi.lms_url') . '/course/' . $course->slug . '/certificate/' . $certificate->id;

        $statement = [
            'actor' => $this->buildActor($user),
            'verb' => ['id' => 'http://id.tincanapi.com/verb/earned', 'display' => ['en-US' => 'earned']],
            'object' => [
                'id' => $certObjectUrl,
                'definition' => [
                    'name' => ['en-US' => ($course->title ?? 'Course') . ' Certificate'],
                    'type' => 'https://www.opigno.org/en/tincan_registry/activity_type/certificate',
                ],
                'objectType' => 'Activity',
            ],
            'context' => [
                'extensions' => array_merge(
                    $this->buildPlatformExtension(),
                    ['http://id.tincanapi.com/extension/jws-certificate-location' => $certUrl]
                ),
                'platform' => config('nelc-xapi.platform_code'),
                'language' => config('nelc-xapi.language', 'ar-SA'),
                'contextActivities' => [
                    'parent' => [$this->buildCourseObject($course, false)], // No description in parent (CTX001)
                ],
            ],
            'timestamp' => now()->toIso8601ZuluString(),
        ];

        $response = $this->sendStatement($statement);
        Log::info('NELC: earned', ['user' => $user->id, 'cert' => $certificate->id, 'status' => $response['status'] ?? null]);
        $this->recordStatement($user->id, 'earned', 'certificate', $certificate->id, $course->id, $response);
        return $response;
    }
}
