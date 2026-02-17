<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransactions extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount'
    ];

    public function CashManager()
    {
        return $this->belongsTo( CashManager::class, 'trnx_id');
    }

}
