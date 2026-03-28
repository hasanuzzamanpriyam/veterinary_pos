<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeldPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'supplier_name',
        'warehouse_id',
        'product_store_id',
        'transport_no',
        'delivery_man',
        'cart_data',
    ];

    protected $casts = [
        'cart_data' => 'array',
    ];
}
