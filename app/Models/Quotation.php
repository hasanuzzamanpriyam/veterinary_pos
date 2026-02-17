<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Quotation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo( customer::class, 'customer_id');
    }
    public function product()
    {
        return $this->belongsTo( Product::class, 'product_id');
    }
    public function brand()
    {
        return $this->belongsTo( Brand::class, 'brand_id');
    }
    public function productGroup()
    {
        return $this->belongsTo( ProductGroup::class, 'group_id');
    }
}
