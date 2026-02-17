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
}
