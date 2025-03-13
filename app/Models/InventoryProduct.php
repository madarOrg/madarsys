<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{
    HasBranch,
    HasUser
};

class InventoryProduct extends Model
{
    use HasBranch, HasUser, HasFactory;

    protected $table = 'inventory_products';

    protected $fillable = [
        'product_id',
        'branch_id',
        'warehouse_id',
        'storage_area_id',
        'location_id',
        'inventory_movement_type',
        'created_user',
        'updated_user',
        'inventory_transaction_item_id',
        'quantity',
        'temporary_transfer_expiry_date',
    ];

    /**
     * العلاقات مع الجداول الأخرى
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function storageArea()
    {
        return $this->belongsTo(WarehouseStorageArea::class, 'storage_area_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_user');
    }
    public function location()
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }
   
    public function transactionItem()
    {
        return $this->belongsTo(InventoryTransactionItem::class, 'inventory_transaction_item_id');
    }
    
}
