<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashManager extends Model
{
    use HasFactory;
    protected $fillable = [
        'prev_balance',
        'collection',
        'payment',
        'expense',
        'home_cash',
        'short_cash',
        'dokan_cash',
    ];

    public function transactions()
    {
        return $this->hasMany( CashTransactions::class);
    }
}
