<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class InventoryTransactionItem extends Model
{
    use HasBranch,HasFactory;

    protected $fillable = [
        'inventory_transaction_id',
        'product_id',
        'quantity',
        'total',
        'warehouse_location_id',
        'branch_id',
        'unit_id',
        'converted_quantity'

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
}
