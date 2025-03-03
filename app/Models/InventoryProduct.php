<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};
class InventoryProduct extends Model
{
    use HasBranch,HasUser,HasFactory;

    protected $table = 'inventory_products';

    protected $fillable = [
        'product_id',
        'branch_id',
        'warehouse_id',
        'storage_area_id',
        'shelf_location',
        'inventory_movement_type',
        'created_user',
        'updated_user',
    ];

    /**
     * العلاقات مع الجداول الأخرى
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
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
}
