<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{HasBranch,
HasUser
};
class WarehouseStorageArea extends Model
{
    use HasUser,HasBranch,HasFactory;

    // تحديد الجدول في قاعدة البيانات
    protected $table = 'warehouse_storage_areas';

    // تحديد الأعمدة التي يمكن ملؤها بشكل جماعي
    protected $fillable = [
        'branch_id',
        'warehouse_id',
        'area_name',
        'area_type',
        'capacity',
        'current_occupancy',
        'zone_id',
        'storage_conditions',
        'created_user', 'updated_user'];

    // تحديد العلاقة مع المستودعات
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    //  تحديد العلاقة مع المناطق الفرعي)
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    
     /**
     * العلاقة مع مواقع التخزين التابعة لهذه المنطقة.
     */
    public function warehouseLocations()
    {
        return $this->hasMany(WarehouseLocation::class, 'storage_area_id');
    }

}
