<?php

namespace App\Jobs;

use App\Services\SaleDataFetcher;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Джоб за синхронизации заказов
 */
class FetchSalesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Запуск обработки данных
     *
     * @param SaleDataFetcher $salesDataFetcher
     * @return void
     * @throws Exception|Throwable
     */
    public function handle(SaleDataFetcher $salesDataFetcher): void
    {
        Log::info('FetchSalesJob started');

        try {
            $salesDataFetcher->fetchSales();
            Log::info('FetchSalesJob completed');
        } catch (Throwable $e) {
            Log::error('FetchSalesJob failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
