<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Contracts\Repositories\StockRepositoryInterface;
use Exception;

/**
 * Остатки. Сервисный слой
 */
class StockDataFetcher
{
    private string $host;
    private array $endpoint;
    private string $apiKey;
    private int $limit;
    private int $requestDelayMs;

    public function __construct(
        private readonly StockRepositoryInterface $stockRepository
    ) {
        $this->host = config('api_target.host');
        $this->endpoint = config('api_target.endpoints');
        $this->apiKey = config('api_target.key');
        $this->limit = config('api_target.limit');
        $this->requestDelayMs = config('api_target.request_delay_ms');
    }

    /**
     * Метод выгрузки остатков из целевого API
     * Особенность: выгрузка только за текущий день
     *
     * @return void
     * @throws Exception
     */
    public function fetchStocks(): void
    {
        $page = 1;
        $totalFromApi = 0;
        $today = now()->format('Y-m-d');

        $this->stockRepository->truncate();
        $countBefore = 0;
        Log::info('STOCKS. Start fetching', ['records_in_db_before' => $countBefore]);

        do {
            $url = $this->host . $this->endpoint['stocks'];

            $response = Http::get($url, [
                'dateFrom' => $today,
                'page' => $page,
                'limit' => $this->limit,
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch stocks page {$page}: " . $response->status());
            }

            $data = $response->json();
            if (!empty($data['data'])) {
                $this->stockRepository->upsertBatch($data['data']);
                $totalFromApi += count($data['data']);
                Log::info("STOCKS. Page {$page} processed", [
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
        $countAfter = $this->stockRepository->count();
        $newRecords = $countAfter - $countBefore;

        Log::info('STOCKS. Fetching completed', [
            'records_in_db_before' => $countBefore,
            'records_in_db_after' => $countAfter,
            'new_records_added' => $newRecords,
            'records_received_from_api' => $totalFromApi,
        ]);
    }
}
