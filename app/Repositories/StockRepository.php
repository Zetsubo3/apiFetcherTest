<?php

namespace App\Repositories;

use App\Contracts\Repositories\StockRepositoryInterface;
use App\Models\Stock;

class StockRepository implements StockRepositoryInterface
{
    /**
     * Массовая вставка в таблицу через upsert
     * через композит кей
     *
     * @param array $items
     * @return void
     */
    public function upsertBatch(array $items): void
    {
        if (empty($items)) {
            return;
        }

        Stock::query()->upsert(
            $items,
            ['nm_id', 'date', 'warehouse_name'],
            [
                'barcode',
                'supplier_article',
                'tech_size',
                'quantity',
                'quantity_full',
                'in_way_to_client',
                'in_way_from_client',
                'subject',
                'category',
                'brand',
                'sc_code',
                'price',
                'discount',
                'last_change_date',
                'is_supply',
                'is_realization',
            ]
        );
    }

    /**
     * Возвращает кол-во записей в таблице
     * @return int
     */
    public function count(): int
    {
        return Stock::query()->count();
    }

    /**
     * Очищает таблицу
     * @return void
     */
    public function truncate(): void
    {
        Stock::query()->truncate();
    }
}
