<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'name',
        'address',
        'contact_info',
        'branch_id',
        'supervisor_id',
        'latitude',
        'longitude',
        'area',
        'shelves_count',
        'capacity',
        'is_smart',
        'has_security_system',
        'has_cctv',
        'is_integrated_with_wms',
        'last_maintenance',
        'has_automated_systems',
        'temperature',
        'humidity',
        'code',
        'is_active'
    ];

    // علاقة المستودع مع الفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // علاقة المستودع مع الأدوار
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_warehouse')
            ->withPivot('branch_id', 'company_id')
            ->withTimestamps();
    }

    // علاقة المستودع مع المشرف
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    // تحديد العلاقة مع مناطق التخزين
    public function storageAreas()
    {
        return $this->hasMany(WarehouseStorageArea::class);
    }
    // تحديد العلاقة مع المواقع التخزينية
    public function warehouseLocations()
    {
        return $this->hasMany(WarehouseLocation::class);
    }
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
