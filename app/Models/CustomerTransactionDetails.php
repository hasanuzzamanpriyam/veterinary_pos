<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransactionDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    // To query https://g.co/gemini/share/f90024ae44a4

    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'unit_price', 'discount', 'total_amount'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'transaction_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'transaction_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'transaction_id', 'id');
    }

}
