<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Contracts\Repositories\OrderRepositoryInterface;

/**
 * Заказы. Сервисный слой
 */
class OrderDataFetcher
{
    private string $host;
    private array $endpoint;
    private string $apiKey;
    private string $dateFrom;
    private string $dateTo;
    private int $limit;
    private int $requestDelay;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {
        $this->host = config('api_target.host');
        $this->endpoint = config('api_target.endpoints');
        $this->apiKey = config('api_target.key');
        $this->dateFrom = config('api_target.date_from');
        $this->dateTo = config('api_target.date_to');
        $this->limit = config('api_target.limit');
        $this->requestDelay = config('api_target.request_delay');
    }

    /**
     * Метод выгрузки заказов из целевого API
     *
     * @return void
     * @throws Exception
     */
    public function fetchOrders(): void
    {
        $page = 1;
        $totalFromApi = 0;

        // подсчёт записей до выгрузки
        $countBefore = $this->orderRepository->count();
        Log::info('ORDERS. Start fetching', ['records_in_db_before' => $countBefore]);

        do {
            $url = $this->host . $this->endpoint['orders'];

            $response = Http::get($url, [
                'dateFrom' => $this->dateFrom,
                'dateTo' => $this->dateTo,
                'page' => $page,
                'limit' => $this->limit,
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch orders page {$page}: " . $response->status());
            }

            $data = $response->json();
            if (!empty($data['data'])) {
                $this->orderRepository->upsertBatch($data['data']);
                $totalFromApi += count($data['data']);
                Log::info("ORDERS. Page {$page} processed", [
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
            sleep($this->requestDelay);
            $page++;

        } while ($hasNext);

        // подсчёт записей после выгрузки
        $countAfter = $this->orderRepository->count();
        $newRecords = $countAfter - $countBefore;

        Log::info('ORDERS. Fetching completed', [
            'records_in_db_before' => $countBefore,
            'records_in_db_after' => $countAfter,
            'new_records_added' => $newRecords,
            'records_received_from_api' => $totalFromApi,
        ]);
    }
}
