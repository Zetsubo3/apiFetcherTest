<?php

namespace App\Services;

use App\Contracts\Repositories\StockRepositoryInterface;
use Exception;

/**
 * Остатки. Сервисный слой
 */
class StockDataFetcher extends BaseDataFetcher
{
    public function __construct(
        private readonly StockRepositoryInterface $stockRepository
    ) {
        parent::__construct();
        $this->dateFrom = now()->format('Y-m-d');  // stocks выгружается только за текущий день
        $this->dateTo = null;
    }

    protected function getEntityName(): string
    {
        return 'STOCKS';
    }

    protected function getEndpointKey(): string
    {
        return 'stocks';
    }

    protected function getRepository(): StockRepositoryInterface
    {
        return $this->stockRepository;
    }

    protected function shouldCountBefore(): bool
    {
        // stocks использует truncate - считаем после
        return false;
    }

    /**
     * @throws Exception
     */
    public function fetchStocks(): void
    {
        // Очищаем таблицу перед выгрузкой
        $this->stockRepository->truncate();

        $this->fetchWithPagination();
    }
}
