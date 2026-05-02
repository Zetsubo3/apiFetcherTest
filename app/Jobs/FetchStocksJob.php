<?php

namespace App\Jobs;

use App\Services\StockDataFetcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use Exception;

class FetchStocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Запуск обработки данных
     *
     * @param StockDataFetcher $stockDataFetcher
     * @return void
     * @throws Exception|Throwable
     */
    public function handle(StockDataFetcher $stockDataFetcher): void
    {
        Log::info('FetchStocksJob started');

        try {
            $stockDataFetcher->fetchStocks();
            Log::info('FetchStocksJob completed');
        } catch (Throwable $e) {
            Log::error('FetchStocksJob failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
