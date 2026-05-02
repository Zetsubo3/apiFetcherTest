<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'g_number',
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
    ];

    protected $casts = [
        'nm_id' => 'integer',
        'income_id' => 'integer',
        'barcode' => 'integer',
        'total_price' => 'decimal:2',
        'discount_percent' => 'integer',
        'date' => 'datetime',
        'last_change_date' => 'date',
        'is_cancel' => 'boolean',
        'cancel_dt' => 'datetime',
    ];
}
