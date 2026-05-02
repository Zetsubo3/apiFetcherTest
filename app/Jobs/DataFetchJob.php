<?php

namespace App\Jobs;

use App\Services\OrderDataFetcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Общий джоб для запуска сущностных джобов
 */
class DataFetchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Обработка джобов для каждой сущности
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info('DataFetchJob started - dispatching all entity jobs');

        FetchOrdersJob::dispatch();
        FetchSalesJob::dispatch();

        Log::info('DataFetchJob dispatched all jobs');
    }
}
