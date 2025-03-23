<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{
    HasBranch,
    HasUser
};

class InventoryTransactionItem extends Model
{
    use HasUser, HasBranch, HasFactory;

    protected $fillable = [
        'inventory_transaction_id',
        'unit_id',
        'product_id',
        'batch_code',
        'unit_prices',
        'quantity',
        'total',
        'warehouse_location_id',
        'branch_id',
        'converted_quantity',
        'unit_product_id',
        'target_warehouse_id',
        'created_user',
        'updated_user',
        'converted_price',
        'production_date',
        'expiration_date',
        'source_warehouse_id',
    


    ];
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($product) {
            // توليد رقم الدفعة تلقائيًا إذا لم يتم إدخاله
            if (!$product->batch_code) {
                $expirationDate = $product->expiration_date ?? null;
                $product->batch_code = $product->generateBatchNumber($expirationDate);
            }
        });
    }
    
    /**
     * توليد رقم الدفعة
     */
    public function generateBatchNumber($expirationDate = null)
    {
        // الجزء الخاص بالتاريخ: yyyy-mm-dd → yymmdd
        $datePart = $expirationDate ? date('ymd', strtotime($expirationDate)) : '000000';
        $warehouseId = $this->target_warehouse_id ?? $this->source_warehouse_id;

        // //  التحقق من العلاقات
        // if (!$this->$warehouseId) {
        //     throw new \Exception('Warehouse relationship is not loaded');
        // }
        // if (!$this->product) {
        //     throw new \Exception('Product relationship is not loaded');
        // }
    
        // جلب آخر دفعة لنفس المنتج والمستودع وتاريخ الانتهاء
        $lastBatch = self::where('product_id', $this->product_id)
        ->where('target_warehouse_id', $warehouseId) // البحث حسب المستودع
        ->where('expiration_date', $expirationDate)
        ->orderBy('id', 'desc')
        ->value('batch_code');
    
        //استخراج الرقم التسلسلي من آخر دفعة
        $lastSerial = $lastBatch ? (int)substr($lastBatch, -3) : 0;
        $newSerial = str_pad($lastSerial + 1, 3, '0', STR_PAD_LEFT);
    
        // تكوين رقم الدفعة
        return sprintf('%s-%s-%s-%s',
            $this->warehouse->code ?? '000',
            $this->product->sku ?? '000',
            $datePart,
            $newSerial
        );
    }
      
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouseLocation()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
    public function targetWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'target_warehouse_id');
    }


    // العلاقة مع InventoryTransactionItem (علاقة عكسية)
    public function inventoryItems()
    {
        return $this->hasMany(InventoryTransactionItem::class, 'inventory_transaction_id');
    }
    // العلاقة مع InventoryProduct (إضافة علاقة مع InventoryProduct)

    public function inventoryProducts()
    {
        return $this->hasMany(InventoryProduct::class, 'inventory_transaction_item_id', 'id');
    }
}
