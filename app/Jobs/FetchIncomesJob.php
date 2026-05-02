<?php

namespace App\Jobs;

use App\Services\IncomeDataFetcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use Exception;

class FetchIncomesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Запуск обработки данных
     *
     * @param IncomeDataFetcher $incomeDataFetcher
     * @return void
     * @throws Exception|Throwable
     */
    public function handle(IncomeDataFetcher $incomeDataFetcher): void
    {
        Log::info('FetchIncomesJob started');

        try {
            $incomeDataFetcher->fetchIncomes();
            Log::info('FetchIncomesJob completed');
        } catch (Throwable $e) {
            Log::error('FetchIncomesJob failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
