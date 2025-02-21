<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasUser
};

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
        'logo',
        'phone_number',
        'email',
        'address',
        'additional_info',
        'settings',
        'created_user', 'updated_user'
    ];

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
