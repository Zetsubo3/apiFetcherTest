<?php

namespace App\Services;

use App\Contracts\Repositories\IncomeRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Доходы. Сервисный слой
 */
class IncomeDataFetcher
{
    private string $host;
    private array $endpoint;
    private string $apiKey;
    private string $dateFrom;
    private string $dateTo;
    private int $limit;
    private int $requestDelayMs;

    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
        $this->host = config('api_target.host');
        $this->endpoint = config('api_target.endpoints');
        $this->apiKey = config('api_target.key');
        $this->dateFrom = config('api_target.date_from');
        $this->dateTo = config('api_target.date_to');
        $this->limit = config('api_target.limit');
        $this->requestDelayMs = config('api_target.request_delay_ms');
    }

    /**
     * Метод выгрузки доходов из целевого API
     *
     * @return void
     * @throws Exception
     */
    public function fetchIncomes(): void
    {
        $page = 1;
        $totalFromApi = 0;

        // подсчёт записей до выгрузки
        $countBefore = $this->incomeRepository->count();
        Log::info('INCOMES. Start fetching', ['records_in_db_before' => $countBefore]);

        do {
            $url = $this->host . $this->endpoint['incomes'];

            $response = Http::get($url, [
                'dateFrom' => $this->dateFrom,
                'dateTo' => $this->dateTo,
                'page' => $page,
                'limit' => $this->limit,
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch incomes page {$page}: " . $response->status());
            }

            $data = $response->json();
            if (!empty($data['data'])) {
                $this->incomeRepository->upsertBatch($data['data']);
                $totalFromApi += count($data['data']);
                Log::info("INCOMES. Page {$page} processed", [
                    'from_api' => count($data['data']),
                    'total_from_api' => $totalFromApi,
                ]);
            }

            // определяем, есть ли следующая страница
            $hasNext = isset($data['links']['next']) && $data['links']['next'] !== null;

            // освобождаем память
            unset($data, $response);
            gc_collect_cycles();

            // обход рэйт лимитера
            usleep($this->requestDelayMs * 1000);
            $page++;

        } while ($hasNext);

        // подсчёт записей после выгрузки
        $countAfter = $this->incomeRepository->count();
        $newRecords = $countAfter - $countBefore;

        Log::info('INCOMES. Fetching completed', [
            'records_in_db_before' => $countBefore,
            'records_in_db_after' => $countAfter,
            'new_records_added' => $newRecords,
            'records_received_from_api' => $totalFromApi,
        ]);
    }
}
