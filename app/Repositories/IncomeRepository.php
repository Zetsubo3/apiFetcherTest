<?php

namespace App\Repositories;

use App\Contracts\Repositories\IncomeRepositoryInterface;
use App\Models\Income;

class IncomeRepository implements IncomeRepositoryInterface
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

        Income::query()->upsert(
            $items,
            ['nm_id'],
            [
                'number',
                'supplier_article',
                'tech_size',
                'quantity',
                'total_price',
                'warehouse_name',
                'date',
                'last_change_date',
                'date_close',
            ]
        );
    }

    /**
     * Возвращает кол-во записей в таблице
     * @return int
     */
    public function count(): int
    {
        return Income::query()->count();
    }
}
