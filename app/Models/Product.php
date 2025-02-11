<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class Product extends Model
{
    use HasBranch,HasFactory;

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
        'is_active',
        'branch_id'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    // العلاقة مع التصنيف
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Product model (Product.php)
     // app/Models/Product.php


        public function unit()
        {
            return $this->belongsTo(Unit::class, 'unit_id', 'id');
        }
        


    // العلاقة مع المورد
    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }
}
