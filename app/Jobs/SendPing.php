<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Check;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

final class SendPing implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Check $check,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(DatabaseManager $database): void
    {
        $start = now();

        $request = Http::baseUrl(
            url: $this->check->service->url,
        );

        if ($this->check->credentials) {
            // attach the credentials to the request
        }

        if ($this->check->parameters) {
            $request->withQueryParameters(
                parameters: $this->check->parameters->toArray(),
            );
        }

        if ($this->check->headers) {
            $request->withHeaders(
                headers: $this->check->headers->toArray(),
            );
        }

        $response = $request->send(
            method: $this->check->method,
            url: $this->check->path,
            options: [
                'json' => $this->check->json,
            ],
        );

        $stats = $response->transferStats->getHandlerStats();

        $database->transaction(
            callback: fn () => $this->check->reports()->create(
                attributes: [
                    'url' => $stats['url'],
                    'content_type' => $stats['content_type'],
                    'status' => $stats['http_code'],
                    'header_size' => $stats['header_size'],
                    'request_size' => $stats['request_size'],
                    'redirect_count' => $stats['redirect_count'],
                    'http_version' => $stats['http_version'],
                    'appconnect_time' => $stats['appconnect_time_us'],
                    'connect_time' => $stats['connect_time_us'],
                    'namelookup_time' => $stats['namelookup_time_us'],
                    'pretransfer_time' => $stats['pretransfer_time_us'],
                    'redirect_time' => $stats['redirect_time_us'],
                    'starttransfer_time' => $stats['starttransfer_time_us'],
                    'total_time' => $stats['total_time_us'],
                    'started_at' => $start,
                    'finished_at' => now(),
                ],
            ),
            attempts: 3,
        );
    }
}
