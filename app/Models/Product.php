<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo( Category::class, 'category_id');
    }
    public function subcategory()
    {
        return $this->belongsTo( SubCategory::class, 'subcategory_id');
    }
    public function brand()
    {
        return $this->belongsTo( Brand::class, 'brand_id');
    }
    public function productGroup()
    {
        return $this->belongsTo( ProductGroup::class, 'group_id');
    }
    public function size()
    {
        return $this->belongsTo( Size::class, 'size_id');
    }
    public function warehouse()
    {
        return $this->belongsTo( Warehouse::class, 'warehouse_id');
    }

    public function offers()
    {
        return $this->hasMany(ProductOffer::class, 'product_id');
    }

    /**
     * Return the currently active offer for this product if any.
     */
    public function activeOffer()
    {
        $today = now()->toDateString();
        return $this->offers()
            ->where('active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Apply offer (if any) and return discounted price and meta array: ['price' =>, 'offer' => ProductOffer|null]
     */
    public function priceWithOffer($price = null)
    {
        $price = $price ?? $this->selling_rate;
        $offer = $this->activeOffer();
        if (!$offer) {
            return ['price' => (float) $price, 'offer' => null];
        }

        $value = (float) $offer->value;
        if ($offer->type === ProductOffer::TYPE_PERCENTAGE) {
            $discount = ($price * $value) / 100;
        } else {
            $discount = $value;
        }

        $final = (float) max(0, $price - $discount);
        return ['price' => $final, 'offer' => $offer];
    }
}
