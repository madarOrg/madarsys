<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{
    HasBranch,
    HasUser
};

class Zone extends Model
{
    use HasBranch,HasUser, HasFactory;

    protected $fillable = ['name', 'code', 'description', 'warehouse_id', 'created_user', 'updated_user','capacity','current_occupancy','branch_id'];

    // علاقة مع المستودعات 
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    // علاقة مع المناطق التخزينيه 
    public function storageArea()
    {
        return $this->hasMany(WarehouseStorageArea::class);
    }
    // علاقة مع المواقع  
    public function  warehouseLocations()
    {
        return $this->hasMany(WarehouseLocation::class);
    }
}
