<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOffer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['amount', 'description', 'date'];

    public function supplier()
    {
        return $this->belongsTo( Supplier::class, 'supplier_id');
    }

}
