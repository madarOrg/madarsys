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

<<<<<<< HEAD
    // علاقة مع الفئة (Category) 
=======
    public static function generateSKU($product)
    {
        $categoryCode = strtoupper(substr($product->category->code ?? 'GEN', 0, 3)); // أول 3 أحرف من اسم الفئة
        $brandCode = strtoupper(substr($product->brand ?? 'NO-BRAND', 0, 3)); // أول 3 أحرف من العلامة التجارية
        $uniqueId = str_pad(self::max('id') + 1, 6, '0', STR_PAD_LEFT); // رقم فريد من6  أرقام

        return "{$categoryCode}-{$brandCode}-{$uniqueId}";
    }

    // العلاقة مع الفئة
>>>>>>> cd8ff8182ca3a7723128542e0abbd735444d7e74
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة مع المورد (Supplier)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}

