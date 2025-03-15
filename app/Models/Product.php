<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\
{
HasBranch,
HasUser
};

class Product extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $fillable = [
        'name',
        'branch_id',
        'image',
        'description',
        'brand',
        'category_id',
        'supplier_id',
        'barcode',
        'sku',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'min_stock_level',
        'max_stock_level',
        'unit_id',
        'is_active',
        'tax',               // الضريبة (%)
        'discount',          // التخفيضات (%)
        'supplier_contact',  // رقم المورد
        'purchase_date',     // تاريخ الشراء
        'manufacturing_date',// تاريخ التصنيع
        'expiration_date',   // تاريخ انتهاء المنتج
        'last_updated',      // تاريخ آخر تحديث
        'created_user', 'updated_user'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->sku = self::generateSKU($product);
        });
    }

    public static function generateSKU($product)
    {
        $categoryCode = strtoupper(substr($product->category->code ?? 'GEN', 0, 3)); // أول 3 أحرف من اسم الفئة
        $brandCode = strtoupper(substr($product->brand ?? 'NO-BRAND', 0, 3)); // أول 3 أحرف من العلامة التجارية
        $uniqueId = str_pad(self::max('id') + 1, 6, '0', STR_PAD_LEFT); // رقم فريد من6  أرقام

        return "{$categoryCode}-{$brandCode}-{$uniqueId}";
    }

    // العلاقة مع الفئة
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع المورد
    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }

    // العلاقة مع الوحدة
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}
