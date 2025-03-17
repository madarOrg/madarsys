<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'name', 
        'description', 
        'stock', 
        'price'
    ];

    // علاقة مع تفاصيل الطلب
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // علاقة مع الفئة (Category) 
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة مع المورد (Supplier)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

