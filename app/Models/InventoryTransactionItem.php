<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};

class InventoryTransactionItem extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $fillable = [
        'inventory_transaction_id',
        'unit_id',
        'product_id',
        'unit_prices',
        'quantity',
        'total',
        'warehouse_location_id',
        'branch_id',
        'converted_quantity',
        'unit_product_id' ,
        'target_warehouse_id',
        'created_user', 'updated_user' 

    ];
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
}
