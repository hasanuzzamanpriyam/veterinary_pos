<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function warehouse()
    {
        return $this->belongsTo( Warehouse::class, 'warehouse_id');
    }
    public function store()
    {
        return $this->belongsTo( Store::class, 'product_store_id');
    }

    public function product()
    {
        return $this->belongsTo( Product::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo( Supplier::class, 'supplier_id');
    }

    /**
     * Get the regular quantity by subtracting discount quantity from the total product quantity.
     * Ensure it doesn't go below 0.
     */
    public function getRegularQuantityAttribute()
    {
        return max(0, $this->product_quantity - $this->discount_quantity);
    }
}
