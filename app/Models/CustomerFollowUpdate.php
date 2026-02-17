<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFollowUpdate extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo( customer::class, 'customer_id');
    }
}
