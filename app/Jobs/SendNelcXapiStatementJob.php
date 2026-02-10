<?php

namespace App\Jobs;

use App\Models\NelcXapiStatement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Nelc\LaravelNelcXapiIntegration\XapiIntegration;

class SendNelcXapiStatementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    protected int $userId;
    protected ?int $webinarId;
    protected string $verb;
    protected string $objectType;
    protected string $objectId;
    protected array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $userId,
        ?int $webinarId,
        string $verb,
        string $objectType,
        string $objectId,
        array $payload
    ) {
        $this->userId = $userId;
        $this->webinarId = $webinarId;
        $this->verb = $verb;
        $this->objectType = $objectType;
        $this->objectId = $objectId;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $xapi = new XapiIntegration();

            $method = $this->payload['method'] ?? null;
            $args = $this->payload['args'] ?? [];

            if (empty($method) || !method_exists($xapi, $method)) {
                Log::error('NELC xAPI: Invalid method', [
                    'method' => $method,
                    'user_id' => $this->userId,
                    'verb' => $this->verb,
                ]);
                return;
            }

            $response = call_user_func_array([$xapi, $method], $args);

            $status = $response['status'] ?? null;
            $body = $response['body'] ?? null;

            // Log the statement to the database
            try {
                NelcXapiStatement::updateOrCreate(
                    [
                        'user_id' => $this->userId,
                        'verb' => $this->verb,
                        'object_id' => $this->objectId,
                    ],
                    [
                        'webinar_id' => $this->webinarId,
                        'object_type' => $this->objectType,
                        'response_status' => $status,
                        'response_body' => $body,
                        'payload' => $this->payload,
                    ]
                );
            } catch (\Exception $e) {
                // If uniqueness constraint fails, just update
                Log::warning('NELC xAPI: Could not log statement', [
                    'error' => $e->getMessage(),
                    'user_id' => $this->userId,
                    'verb' => $this->verb,
                ]);
            }

            if ($status == 200) {
                Log::info('NELC xAPI: Statement sent successfully', [
                    'verb' => $this->verb,
                    'object_type' => $this->objectType,
                    'user_id' => $this->userId,
                    'webinar_id' => $this->webinarId,
                    'uuid' => $body,
                ]);
            } else {
                Log::error('NELC xAPI: Statement failed', [
                    'verb' => $this->verb,
                    'object_type' => $this->objectType,
                    'user_id' => $this->userId,
                    'status' => $status,
                    'body' => $body,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('NELC xAPI Job error: ' . $e->getMessage(), [
                'user_id' => $this->userId,
                'verb' => $this->verb,
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw so the job is retried
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('NELC xAPI Job permanently failed: ' . $exception->getMessage(), [
            'user_id' => $this->userId,
            'verb' => $this->verb,
            'object_id' => $this->objectId,
        ]);

        // Log the failed statement
        try {
            NelcXapiStatement::updateOrCreate(
                [
                    'user_id' => $this->userId,
                    'verb' => $this->verb,
                    'object_id' => $this->objectId,
                ],
                [
                    'webinar_id' => $this->webinarId,
                    'object_type' => $this->objectType,
                    'response_status' => 0,
                    'response_body' => 'FAILED: ' . $exception->getMessage(),
                    'payload' => $this->payload,
                ]
            );
        } catch (\Exception $e) {
            // Silently fail logging
        }
    }
}
