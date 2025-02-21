<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected $table = 'role_user'; // اسم الجدول

    protected $fillable = [
        'user_id',
        'role_id',
        'created_user', 'updated_user'
        // أي أعمدة إضافية في جدول الربط
    ];

    /**
     * إعداد العلاقة مع المستخدمين.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * إعداد العلاقة مع الأدوار.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
     
}
