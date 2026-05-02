<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'g_number',
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
    ];

    protected $casts = [
        'nm_id' => 'integer',
        'income_id' => 'integer',
        'barcode' => 'integer',
        'total_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'spp' => 'decimal:2',
        'for_pay' => 'decimal:2',
        'finished_price' => 'decimal:2',
        'price_with_disc' => 'decimal:2',
        'promo_code_discount' => 'decimal:2',
        'date' => 'date',
        'last_change_date' => 'date',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'is_storno' => 'boolean',
    ];
}
