<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeldPurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'supplier_name',
        'balance',
        'address',
        'mobile',
        'purchase_date',
        'return_date',
        'warehouse_id',
        'warehouse_name',
        'product_store_id',
        'product_store_name',
        'purchase_invoice_no',
        'delivery_man',
        'remarks',
        'cart_data',
    ];

    protected $casts = [
        'cart_data' => 'array',
    ];
}
