<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\{
    HasBranch,
    HasUser
};

class Product extends Model
{
    use HasFactory, HasUser, HasBranch;

    protected $fillable = [
        'name',
        'branch_id',
        'image',
        'description',
        'brand_id',
        'category_id',
        'supplier_id',
        'barcode',
        'sku',
        'ingredients',
        'notes',
        'manufacturing_country_id',
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
        'manufacturing_date', // تاريخ التصنيع
        'expiration_date',   // تاريخ انتهاء المنتج
        'last_updated',      // تاريخ آخر تحديث
        'created_user',
        'updated_user'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // إنشاء SKU عند إنشاء المنتج
            $product->sku = self::generateSKU($product);
        });
    }

    // دالة لإنشاء SKU فريد
    public static function generateSKU($product)
    {
        $categoryCode = strtoupper($product->category->code ?? 'GEN'); 
        $brandCode = strtoupper($product->brand->code ?? 'NO-BRAND'); 
        $uniqueId = str_pad(self::max('id') + 1, 6, '0', STR_PAD_LEFT); // رقم فريد من 6 أرقام

        return "{$categoryCode}-{$brandCode}-{$uniqueId}";
    }

    // العلاقة مع الفئة
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

   
    // العلاقة مع الوحدة
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    // العلاقة مع الشحنات
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
    public function inventoryTransactionItems()
    {
        return $this->hasMany(InventoryTransactionItem::class, 'product_id');
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'product_id');
    }

    public function inventoryTransactions()
    {
        return $this->hasManyThrough(
            InventoryTransaction::class,
            InventoryTransactionItem::class,
            'product_id',                // المفتاح الأجنبي في InventoryTransactionItem يشير إلى Product
            'id',                        // المفتاح الرئيسي في InventoryTransaction
            'id',                        // المفتاح الرئيسي في Product
            'inventory_transaction_id'   // المفتاح الأجنبي في InventoryTransactionItem يشير إلى InventoryTransaction
        );
    }
    protected $dates = ['purchase_date', 'manufacturing_date', 'expiration_date', 'last_updated'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
 // العلاقة مع المورد
 // العلاقة مع المورد
public function supplier()
{
    return $this->belongsTo(Partner::class, 'supplier_id', 'id'); // تأكد من أن المفتاح الأجنبي هو supplier_id
}


    public function manufacturingCountry()
    {
        return $this->belongsTo(ManufacturingCountry::class);
    }
    public function productOfWarehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'inventory_products')
                    ->withPivot(['quantity', 'expiration_date'])
                    ->withTimestamps();
    }
    public function quantityOfProductOfWarehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'inventory_products')
                    ->withPivot(['quantity'])
                    ->withTimestamps();
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'inventory_products', 'product_id', 'warehouse_id')->distinct();
    }
    
}
