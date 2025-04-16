<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasUser
};
 use App\Models\Scopes\UserAccessScope;


class Company extends Model
{
    use HasUser,HasFactory;

    /**
     * اسم الجدول المرتبط بالمودل.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * الحقول القابلة للملء في المودل.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'logo',           
        'address',         
        'additional_info', 
        'settings',
        'created_user',
        'updated_user',
    ];
    // protected static function booted()
    // {
    //     static::addGlobalScope(new UserAccessScope);
    // }

    public function scopeForUserCompany($query)
    {
        if (auth()->check()) {
            $roleIds = auth()->user()->roles()->pluck('id');
    
            // جلب الشركات بناءً على الأدوار من جدول role_company
            $defaultCompany =Company::whereIn('id', function ($q) use ($roleIds) {
                $q->select('company_id')
                    ->from('role_company')
                    ->whereIn('role_id', $roleIds);
            })->first();
    
            if ($defaultCompany) {
                return $query->where('id', $defaultCompany->id);
            }
        }
        return $query;
    }
    
    /**
     * الحقول التي يتم تحويلها تلقائيًا إلى أنواع أخرى.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array', // يحول حقل الإعدادات إلى مصفوفة
    ];

        //  علاقة الشركة مع الفروع
        public function branches()
        {
            return $this->hasMany(Branch::class);
        }
   
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_company');
    }
    
}
