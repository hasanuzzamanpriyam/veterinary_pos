<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MonthlyBonusCount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'start' => 'decimal:2',
        'end' => 'decimal:2',
        'rate' => 'decimal:4',
    ];

    public function supplier()
    {
        return $this->belongsTo( Supplier::class, 'supplier_id');
    }
}
