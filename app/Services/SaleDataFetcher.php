<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Contracts\Repositories\SaleRepositoryInterface;
use Exception;

/**
 * Продажи. Сервисный слой
 */
class SaleDataFetcher
{
    private string $host;
    private array $endpoint;
    private string $apiKey;
    private string $dateFrom;
    private string $dateTo;
    private int $limit;
    private int $requestDelayMs;

    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository
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
     * Метод выгрузки продаж из целевого API
     *
     * @return void
     * @throws Exception
     */
    public function fetchSales(): void
    {
        $page = 1;
        $totalFromApi = 0;

        // подсчёт записей до выгрузки
        $countBefore = $this->saleRepository->count();
        Log::info('SALES. Start fetching', ['records_in_db_before' => $countBefore]);

        do {
            $url = $this->host . $this->endpoint['sales'];

            $response = Http::get($url, [
                'dateFrom' => $this->dateFrom,
                'dateTo' => $this->dateTo,
                'page' => $page,
                'limit' => $this->limit,
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch sales page {$page}: " . $response->status());
            }

            $data = $response->json();
            if (!empty($data['data'])) {
                $this->saleRepository->upsertBatch($data['data']);
                $totalFromApi += count($data['data']);
                Log::info("SALES. Page {$page} processed", [
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

        // Подсчёт записей после выгрузки
        $countAfter = $this->saleRepository->count();
        $newRecords = $countAfter - $countBefore;

        Log::info('SALES. Fetching completed', [
            'records_in_db_before' => $countBefore,
            'records_in_db_after' => $countAfter,
            'new_records_added' => $newRecords,
            'records_received_from_api' => $totalFromApi,
        ]);
    }
}
