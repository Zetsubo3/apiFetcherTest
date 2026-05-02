<?php

namespace App\Repositories;

use App\Contracts\Repositories\SaleRepositoryInterface;
use App\Models\Sale;

class SaleRepository implements SaleRepositoryInterface
{
    /**
     * Массовая вставка в таблицу через upsert
     * nm_id - уникальный идентификатор продажи
     *
     * @param array $items
     * @return void
     */
    public function upsertBatch(array $items): void
    {
        if (empty($items)) {
            return;
        }

        Sale::query()->upsert(
            $items, ['nm_id'],
            [
                'sale_id',
                'nm_id',
                'income_id',
                'supplier_article',
                'tech_size',
                'barcode',
                'total_price',
                'discount_percent',
                'spp',
                'for_pay',
                'finished_price',
                'price_with_disc',
                'promo_code_discount',
                'warehouse_name',
                'country_name',
                'oblast_okrug_name',
                'region_name',
                'subject',
                'category',
                'brand',
                'date',
                'last_change_date',
                'is_supply',
                'is_realization',
                'is_storno',
                'odid',
            ]
        );
    }

    /**
     * Возвращает кол-во записей в таблице
     * @return int
     */
    public function count(): int
    {
        return Sale::query()->count();
    }
}
