<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\ProductSyncService;
use Illuminate\Support\Facades\Log;

class SyncProductsJob implements ShouldQueue
{
    use Queueable;
    public int $tries = 5;
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ProductSyncService $service): void
    {
        Log::debug('SyncProductsJob: executando handle()');

        $success = $service->sync();

        if (!$success) {
            throw new \Exception("Falha ao sincronizar produtos com a API externa.");
        }
    }

    /**
     * Caso de falha
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical('SyncProductsJob falhou após todas as tentativas.', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

}
