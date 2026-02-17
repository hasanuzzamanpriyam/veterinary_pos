<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function ledgers()
    {
        return $this->hasMany( SupplierLedger::class);
    }

    public function transactions()
    {
        return $this->hasMany( SupplierTransactionDetails::class);
    }

    public function supplierLedger(){
        return $this->belongsTo( SupplierLedger::class, 'supplier_id');
    }
    public function bonusRates(){
        return $this->hasOne( SupplierBonus::class, 'supplier_id');
    }
}
