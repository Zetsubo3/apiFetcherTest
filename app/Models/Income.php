<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'income_id',
        'nm_id',
        'barcode',
        'number',
        'supplier_article',
        'tech_size',
        'quantity',
        'total_price',
        'warehouse_name',
        'date',
        'last_change_date',
        'date_close',
    ];

    protected $casts = [
        'income_id' => 'integer',
        'nm_id' => 'integer',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
        'date' => 'date',
        'last_change_date' => 'date',
        'date_close' => 'date',
    ];
}
