<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'contact_info', 'company_id',
    ];

    // علاقة الفرع مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة الفرع مع المستودعات
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_branch')
                    ->withPivot('company_id')
                    ->withTimestamps();
    }
  
}
