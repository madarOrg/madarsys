<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnSuppliersOrder extends Model
{
    protected $fillable = [
        'id',
        'supplier_id',
        'status',
        'return_reason',
        'return_date',
        'created_at',
        'updated_at',
        'return_number'
    ];

    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }
    public function items()
{
    return $this->hasMany(ReturnSuppliersOrderItem::class, 'return_supplier_order_id');
}

}
