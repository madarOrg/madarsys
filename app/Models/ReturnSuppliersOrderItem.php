<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnSuppliersOrderItem extends Model
{
    protected $fillable = [
        'id',
        'return_supplier_order_id',
        'product_id',
        'quantity',
        'status',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function returnSuppliersOrder()
    {
        return $this->belongsTo(ReturnSuppliersOrder::class, 'return_supplier_order_id'); // تأكد من أن المفتاح الأجنبي هو 'return_order_id'
    }
}
