<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'nm_id',
        'barcode',
        'supplier_article',
        'tech_size',
        'quantity',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
        'date',
        'last_change_date',
        'is_supply',
        'is_realization',
    ];

    protected $casts = [
        'nm_id' => 'integer',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'quantity_full' => 'integer',
        'in_way_to_client' => 'integer',
        'in_way_from_client' => 'integer',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'date' => 'date',
        'last_change_date' => 'date',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
    ];
}
