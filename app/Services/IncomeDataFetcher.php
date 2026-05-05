<?php

namespace App\Services;

use App\Contracts\Repositories\IncomeRepositoryInterface;
use Exception;

/**
 * Доходы. Сервисный слой
 */
class IncomeDataFetcher extends BaseDataFetcher
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
        parent::__construct();
    }

    protected function getEntityName(): string
    {
        return 'INCOMES';
    }

    protected function getEndpointKey(): string
    {
        return 'incomes';
    }

    protected function getRepository(): IncomeRepositoryInterface
    {
        return $this->incomeRepository;
    }

    /**
     * @throws Exception
     */
    public function fetchIncomes(): void
    {
        $this->fetchWithPagination();
    }
}
