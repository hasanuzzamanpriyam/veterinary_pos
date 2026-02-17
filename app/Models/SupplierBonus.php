<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierBonus extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'yearly', 'monthly'];

    public function monthlyBonus(){
        return $this->hasOne( MonthlyBonusCount::class, 'supplier_id');
    }
    public function yearlyBonus(){
        return $this->hasOne( YearlyBonusCount::class, 'supplier_id');
    }

    public function supplier(){
        return $this->belongsTo( Supplier::class, 'supplier_id');
    }
}
