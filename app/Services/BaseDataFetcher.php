<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseDataFetcher
{
    protected string $host;
    protected array $endpoint;
    protected string $apiKey;
    protected int $limit;
    protected int $requestDelayMs;
    protected ?string $dateFrom = null;
    protected ?string $dateTo = null;

    public function __construct()
    {
        $this->host = config('api_target.host');
        $this->endpoint = config('api_target.endpoints');
        $this->apiKey = config('api_target.key');
        $this->limit = config('api_target.limit');
        $this->requestDelayMs = config('api_target.request_delay_ms');
        $this->dateFrom = config('api_target.date_from');
        $this->dateTo = config('api_target.date_to');
    }

    /**
     * Получить название сущности для логов
     */
    abstract protected function getEntityName(): string;

    /**
     * Получить ключ эндпоинта (orders, sales, stocks, incomes)
     */
    abstract protected function getEndpointKey(): string;

    /**
     * Получить параметры запроса для текущей страницы
     */
    protected function getRequestParams(int $page): array
    {
        $params = [
            'page' => $page,
            'limit' => $this->limit,
            'key' => $this->apiKey,
        ];

        if ($this->dateFrom !== null) {
            $params['dateFrom'] = $this->dateFrom;
        }
        if ($this->dateTo !== null) {
            $params['dateTo'] = $this->dateTo;
        }

        return $params;
    }

    /**
     * Выполнить выгрузку с пагинацией
     * @throws Exception
     */
    protected function fetchWithPagination(): void
    {
        $page = 1;
        $totalFromApi = 0;
        $entityName = $this->getEntityName();
        $repository = $this->getRepository();

        // Для stocks не считаем до выгрузки (truncate)
        $countBefore = $this->shouldCountBefore() ? $repository->count() : 0;
        Log::info("{$entityName}. Start fetching", ['records_in_db_before' => $countBefore]);

        do {
            $url = $this->host . $this->endpoint[$this->getEndpointKey()];

            $response = Http::get($url, $this->getRequestParams($page));

            if (!$response->successful()) {
                throw new Exception("Failed to fetch {$entityName} page {$page}: " . $response->status());
            }

            $data = $response->json();
            if (!empty($data['data'])) {
                $repository->upsertBatch($data['data']);
                $totalFromApi += count($data['data']);
                Log::info("{$entityName}. Page {$page} processed", [
                    'from_api' => count($data['data']),
                    'total_from_api' => $totalFromApi,
                ]);
            }

            $hasNext = isset($data['links']['next']) && $data['links']['next'] !== null;

            unset($data, $response);
            gc_collect_cycles();

            usleep($this->requestDelayMs * 1000);
            $page++;

        } while ($hasNext);

        $countAfter = $repository->count();
        $newRecords = $this->shouldCountBefore() ? $countAfter - $countBefore : $countAfter;

        Log::info("{$entityName}. Fetching completed", [
            'records_in_db_before' => $countBefore,
            'records_in_db_after' => $countAfter,
            'new_records_added' => $newRecords,
            'records_received_from_api' => $totalFromApi,
        ]);
    }

    /**
     * Нужно ли считать записи ДО выгрузки
     * Для stocks возвращаем false (там truncate)
     */
    protected function shouldCountBefore(): bool
    {
        return true;
    }

    /**
     * Получить репозиторий
     */
    abstract protected function getRepository();
}
