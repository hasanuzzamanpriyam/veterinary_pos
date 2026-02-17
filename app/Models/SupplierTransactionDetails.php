<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierTransactionDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'unit_price', 'discount', 'total_amount'];
    public function warehouse()
    {
        return $this->belongsTo( Warehouse::class, 'warehouse_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo( Supplier::class, 'supplier_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo( Product::class, 'product_id', 'id');
    }
}
