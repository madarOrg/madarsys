<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasUser
};
use App\Models\Scopes\UserAccessScope;

class Warehouse extends Model
{
    use HasUser,HasFactory;

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
        'is_active',
        'created_user', 'updated_user'
    ];
    // protected static function booted()
    // {
    //     static::addGlobalScope(new UserAccessScope);
    // }

    public function scopeForUserWarehouse($query)
    {
        // إذا فيه مستودع محفوظ في الجلسة نستخدمه مباشرة
        if (session()->has('selected_warehouse_id')) {
            return $query->where('id', session('selected_warehouse_id'));
        }
    
        // إذا المستخدم مسجل دخول
        if (auth()->check()) {
            $user = auth()->user();
    
            // جلب المستودعات المتاحة بناءً على الأدوار المرتبطة بالمستخدم
            $accessibleWarehouses = $user->roles()
                ->with('warehouses')
                ->get()
                ->flatMap(fn($role) => $role->warehouses)
                ->unique('id');
    
            // إذا فيه مستودعات متاحة
            if ($accessibleWarehouses->isNotEmpty()) {
                return $query->whereIn('id', $accessibleWarehouses->pluck('id'));
            }
        }
    
        // إذا ما فيه مستودع في الجلسة ولا مستودعات للمستخدم
        return $query;
    }
    
    public function scopeForUserBranch($query)
    {
        if (auth()->check()) {
            $roleIds = auth()->user()->roles()->pluck('id');
            $defaultBranch = \App\Models\Branch::whereIn('id', function($q) use ($roleIds) {
                $q->select('branch_id')
                  ->from('role_branch')
                  ->whereIn('role_id', $roleIds);
            })->first();
    //  dump($defaultBranch);
            if ($defaultBranch) {
                return $query->where('branch_id', $defaultBranch->id);
            }
        }
        // dump($query->get());
        return $query;
    }
   
    

    
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
    //  العلاقة مع المناطق Zones 
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
}
