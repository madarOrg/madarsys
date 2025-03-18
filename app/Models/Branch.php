<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasUser
};
// use App\Models\Scopes\UserAccessScope;


class Branch extends Model
{
    use HasUser,HasFactory;

    protected $fillable = [
        'name', 'address', 'contact_info', 'company_id','created_user', 'updated_user'
    ];
    // protected static function booted()
    // {
    //     static::addGlobalScope(new UserAccessScope);
    // }

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
                ->using(RoleBranch::class) // استخدام النموذج المخصص
                ->withPivot('company_id')
                ->withTimestamps();
}

}
