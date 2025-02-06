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
        'category_id',
        'supplier_id',
        'barcode',
        'sku',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'min_stock_level',
        'max_stock_level',
        'unit',
        'is_active'
    ];

    // العلاقة مع التصنيف
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع المورد
    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }
}
