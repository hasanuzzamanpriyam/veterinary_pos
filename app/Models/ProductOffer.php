<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'decimal:4',
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
    ];

    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_AMOUNT = 'amount';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
