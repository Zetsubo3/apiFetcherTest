<?php

namespace App\Jobs;

use App\Services\OrderDataFetcher;
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
class FetchOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Запуск обработки данных
     *
     * @param OrderDataFetcher $orderDataFetcher
     * @return void
     * @throws Exception
     */
    public function handle(OrderDataFetcher $orderDataFetcher): void
    {
        Log::info('FetchOrdersJob started');

        try {
            $orderDataFetcher->fetchOrders();
            Log::info('FetchOrdersJob completed');
        } catch (Throwable $e) {
            Log::error('FetchOrdersJob failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
