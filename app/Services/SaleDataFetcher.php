<?php

namespace App\Services;

use App\Contracts\Repositories\SaleRepositoryInterface;
use Exception;

/**
 * Продажи. Сервисный слой
 */
class SaleDataFetcher extends BaseDataFetcher
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository
    ) {
        parent::__construct();
    }

    protected function getEntityName(): string
    {
        return 'SALES';
    }

    protected function getEndpointKey(): string
    {
        return 'sales';
    }

    protected function getRepository(): SaleRepositoryInterface
    {
        return $this->saleRepository;
    }

    /**
     * @throws Exception
     */
    public function fetchSales(): void
    {
        $this->fetchWithPagination();
    }
}
