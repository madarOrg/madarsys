<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'order_id', 
        'product_id', 
        'unit_id',
        'quantity', 
        'price', 
        'total_price'
    ];

    // علاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // علاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    // دالة لحساب السعر الكلي بناءً على الكمية والسعر
    public static function boot()
    {
        parent::boot();

        // عند حفظ التفاصيل، يتم حساب السعر الكلي
        static::saving(function ($orderDetail) {
            $orderDetail->total_price = $orderDetail->quantity * $orderDetail->price;
        });
    }
}

