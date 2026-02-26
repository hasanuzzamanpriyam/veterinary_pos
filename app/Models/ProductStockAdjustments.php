<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockAdjustments extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo( Product::class, 'product_id');
    }

    public function priceGroup()
    {
        return $this->belongsTo( PriceGroup::class, 'price_group_id');
    }

    public function warehouse()
    {
        return $this->belongsTo( Warehouse::class, 'warehouse_id');
    }

    public function sourceStore()
    {
        return $this->belongsTo( Store::class, 'source_store_id');
    }

    public function destinationStore()
    {
        return $this->belongsTo( Store::class, 'destination_store_id');
    }


    public function priceGroupProduct()
    {
        return $this->belongsTo( PriceGroupProduct::class, 'price_group_id');
    }
}
