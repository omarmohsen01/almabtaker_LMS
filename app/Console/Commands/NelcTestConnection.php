<?php

namespace App\Console\Commands;

use App\Services\NelcXapiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class NelcTestConnection extends Command
{
    protected $signature = 'nelc:test {--user-id= : User ID to test with} {--course-id= : Course ID to test with} {--full : Run full learning journey test}';
    protected $description = 'Test NELC xAPI LRS connection and send test statements';

    public function handle()
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║     NELC xAPI Integration Test Tool      ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        // Step 1: Check configuration
        $this->info('🔧 Step 1: Checking configuration...');
        $this->checkConfig();

        // Step 2: Test raw connection
        $this->info('');
        $this->info('🌐 Step 2: Testing LRS connection...');
        $this->testConnection();

        // Step 3: If user and course provided, test statements
        $userId = $this->option('user-id');
        $courseId = $this->option('course-id');
        $full = $this->option('full');

        if ($userId && $courseId) {
            $this->info('');
            $this->info('📤 Step 3: Sending test xAPI statements...');
            $this->testStatements((int)$userId, (int)$courseId, $full);
        } else {
            $this->info('');
            $this->warn('ℹ️  To test sending statements, run:');
            $this->line('   php artisan nelc:test --user-id=USER_ID --course-id=COURSE_ID');
            $this->line('   php artisan nelc:test --user-id=USER_ID --course-id=COURSE_ID --full');
        }

        $this->info('');
        $this->info('✅ Test completed!');
    }

    private function checkConfig()
    {
        $checks = [
            'LRS_ENDPOINT' => config('nelc-xapi.endpoint'),
            'LRS_USERNAME' => config('nelc-xapi.key'),
            'LRS_PASSWORD' => config('nelc-xapi.secret'),
            'Platform Code' => config('nelc-xapi.platform_code'),
            'Platform AR' => config('nelc-xapi.platform_in_arabic'),
            'Platform EN' => config('nelc-xapi.platform_in_english'),
            'LMS URL' => config('nelc-xapi.lms_url'),
            'Enabled' => config('nelc-xapi.enabled') ? 'Yes' : 'No',
        ];

        $allOk = true;
        foreach ($checks as $key => $value) {
            if (empty($value) && $key !== 'Enabled') {
                $this->error("   ✗ {$key}: MISSING");
                $allOk = false;
            } else {
                $display = $value;
                if (in_array($key, ['LRS_PASSWORD'])) {
                    $display = str_repeat('*', min(strlen($value), 8)) . '...';
                }
                $this->line("   ✓ {$key}: {$display}");
            }
        }

        if (!$allOk) {
            $this->error('   ⚠ Some configuration values are missing! Check your .env file.');
        }
    }

    private function testConnection()
    {
        try {
            // Send a minimal test - just a ping-style request
            $response = Http::withBasicAuth(
                config('nelc-xapi.key'),
                config('nelc-xapi.secret')
            )
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Experience-API-Version' => '1.0.3',
                ])
                ->timeout(15)
                ->post(config('nelc-xapi.endpoint'), [
                    'actor' => [
                        'mbox' => 'mailto:test@almabtaker.com',
                        'name' => '0000000000',
                        'objectType' => 'Agent',
                    ],
                    'verb' => [
                        'id' => 'http://adlnet.gov/expapi/verbs/registered',
                        'display' => ['en-US' => 'registered'],
                    ],
                    'object' => [
                        'id' => config('nelc-xapi.lms_url') . '/course/connection-test',
                        'definition' => [
                            'name' => ['en-US' => 'Connection Test'],
                            'description' => ['en-US' => 'Testing LRS connectivity'],
                            'type' => 'https://w3id.org/xapi/cmi5/activitytype/course',
                        ],
                        'objectType' => 'Activity',
                    ],
                    'context' => [
                        'platform' => config('nelc-xapi.platform_code'),
                        'language' => 'ar-SA',
                        'extensions' => [
                            'https://nelc.gov.sa/extensions/platform' => [
                                'name' => [
                                    'ar-SA' => config('nelc-xapi.platform_in_arabic'),
                                    'en-US' => config('nelc-xapi.platform_in_english'),
                                ],
                            ],
                        ],
                    ],
                    'timestamp' => now()->toIso8601ZuluString(),
                ]);

            $status = $response->status();
            $body = $response->body();

            if ($response->successful()) {
                $this->info("   ✓ Connection SUCCESS! HTTP {$status}");
                $this->info("   ✓ LRS Response (UUID): {$body}");
                $this->info('   ✓ Your LRS credentials are working correctly!');
            } else {
                $this->error("   ✗ Connection FAILED! HTTP {$status}");
                $this->error("   ✗ Response: {$body}");

                if ($status === 401) {
                    $this->error('   ⚠ Authentication failed. Check your LRS_USERNAME and LRS_PASSWORD in .env');
                } elseif ($status === 403) {
                    $this->error('   ⚠ Access forbidden. Your credentials may not have write access.');
                } elseif ($status === 400) {
                    $this->error('   ⚠ Bad request. The statement format may be incorrect.');
                }
            }
        } catch (\Exception $e) {
            $this->error("   ✗ Connection ERROR: " . $e->getMessage());
        }
    }

    private function testStatements(int $userId, int $courseId, bool $full = false)
    {
        $user = \App\User::find($userId);
        if (!$user) {
            $this->error("   ✗ User with ID {$userId} not found!");
            return;
        }

        $course = \App\Models\Webinar::find($courseId);
        if (!$course) {
            $this->error("   ✗ Course with ID {$courseId} not found!");
            return;
        }

        $this->info("   User: {$user->full_name} (national_id: {$user->national_id})");
        $this->info("   Course: {$course->title}");

        if (empty($user->national_id)) {
            $this->error('   ✗ User has no national_id! NELC requires it. Set it first.');
            return;
        }

        if (!preg_match('/^[124]\d{9}$/', $user->national_id)) {
            $this->warn("   ⚠ national_id '{$user->national_id}' doesn't match NELC format (10 digits, starts with 1/2/4)");
        }

        $nelc = new NelcXapiService();

        // 1. Registered
        $this->line('');
        $this->line('   📤 Sending: registered...');
        $result = $nelc->sendRegistered($user, $course);
        $this->printResult($result);

        // 2. Initialized
        $this->line('   📤 Sending: initialized...');
        $result = $nelc->sendInitialized($user, $course);
        $this->printResult($result);

        if ($full) {
            // 3. Progressed (30%)
            $this->line('   📤 Sending: progressed (30%)...');
            $result = $nelc->sendProgressed($user, $course, 30);
            $this->printResult($result);

            // 4. Completed course
            $this->line('   📤 Sending: completed course...');
            $result = $nelc->sendCompletedCourse($user, $course);
            $this->printResult($result);

            // 5. Rated
            $this->line('   📤 Sending: rated (4.5/5)...');
            $result = $nelc->sendRated($user, $course, 4.5, 'Great course!');
            $this->printResult($result);

            // 6. Earned (fake certificate)
            $cert = \App\Models\Certificate::where('webinar_id', $course->id)
                ->where('student_id', $user->id)
                ->first();
            if ($cert) {
                $this->line('   📤 Sending: earned (certificate)...');
                $result = $nelc->sendEarned($user, $course, $cert);
                $this->printResult($result);
            } else {
                $this->warn('   ⏭ Skipped: earned — no certificate found for this user+course');
            }
        }

        // Show tracking table
        $this->info('');
        $this->info('   📊 Statements recorded in database:');
        $statements = \Illuminate\Support\Facades\DB::table('nelc_xapi_statements')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->get();

        if ($statements->isEmpty()) {
            $this->warn('   No statements recorded (all may have been duplicates or failed)');
        } else {
            $headers = ['Verb', 'Object Type', 'Status', 'Sent At'];
            $rows = $statements->map(function ($s) {
                return [$s->verb, $s->object_type, $s->status_code, $s->sent_at];
            })->toArray();
            $this->table($headers, $rows);
        }
    }

    private function printResult(?array $result)
    {
        if ($result === null) {
            $this->warn('   → Skipped (duplicate or user invalid)');
            return;
        }

        $status = $result['status'] ?? 'N/A';
        $body = $result['body'] ?? '';

        if ($status == 200) {
            $this->info("   → ✓ HTTP {$status} | UUID: {$body}");
        } else {
            $this->error("   → ✗ HTTP {$status} | Response: " . substr($body, 0, 200));
        }
    }
}
