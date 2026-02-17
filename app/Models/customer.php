<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function priceGroup()
    {
        return $this->belongsTo( PriceGroup::class, 'price_group_id');
    }

    public function ledgers()
    {
        return $this->hasMany( CustomerLedger::class);
    }

    public function transactions()
    {
        return $this->hasMany( CustomerTransactionDetails::class);
    }

    public function customerLedger(){
        return $this->belongsTo( CustomerLedger::class, 'customer_id');
    }





}
