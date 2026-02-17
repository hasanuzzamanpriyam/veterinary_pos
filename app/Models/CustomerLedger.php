<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance'
    ];

    public function warehouse()
    {
        return $this->belongsTo( Warehouse::class, 'warehouse_id');
    }

    public function customer()
    {
        return $this->belongsTo( customer::class, 'customer_id');
    }

    public function store()
    {
        return $this->belongsTo( Store::class, 'product_store_id');
    }

    public function transactions()
    {
        return $this->hasMany( CustomerTransactionDetails::class, 'transaction_id');
    }
}
