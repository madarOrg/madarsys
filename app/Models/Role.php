<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

   
     /**
     * العلاقة بين الأدوار والمستخدمين (Many-to-Many)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->using(RoleUser::class) // استخدام Pivot مخصص
            ->withTimestamps(); // إضافة الطوابع الزمنية
    }

    public function rolecompanies()
    {
        return $this->belongsToMany(Company::class, 'role_company');
    }
    
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'role_branch')
                    ->withPivot('company_id') // إضافة الشركة للمعلومات
                    ->withTimestamps();
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'role_warehouse')
                    ->withPivot('branch_id', 'company_id') // إضافة الفرع والشركة
                    ->withTimestamps();
    }
    
}
