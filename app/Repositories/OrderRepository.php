<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Массовая вставка в таблицу через upsert
     * nm_id - идентификатор
     *
     * @param array $items
     * @return void
     */
    public function upsertBatch(array $items): void
    {
        if (empty($items)) {
            return;
        }

        Order::query()->upsert(
            $items, ['nm_id'],
            [
                'nm_id',
                'odid',
                'income_id',
                'supplier_article',
                'tech_size',
                'barcode',
                'total_price',
                'discount_percent',
                'warehouse_name',
                'oblast',
                'subject',
                'category',
                'brand',
                'date',
                'last_change_date',
                'is_cancel',
                'cancel_dt',
            ]
        );
    }

    /**
     * Возвращает кол-во записей в таблице
     * @return int
     */
    public function count(): int
    {
        return Order::query()->count();
    }
}
