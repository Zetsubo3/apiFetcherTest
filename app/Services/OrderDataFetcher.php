<?php

namespace App\Services;

use Exception;
use App\Contracts\Repositories\OrderRepositoryInterface;

/**
 * Заказы. Сервисный слой
 */
class OrderDataFetcher extends BaseDataFetcher
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct();
    }

    protected function getEntityName(): string
    {
        return 'ORDERS';
    }

    protected function getEndpointKey(): string
    {
        return 'orders';
    }

    protected function getRepository(): OrderRepositoryInterface
    {
        return $this->orderRepository;
    }

    /**
     * @throws Exception
     */
    public function fetchOrders(): void
    {
        $this->fetchWithPagination();
    }
}
