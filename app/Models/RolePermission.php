<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // تأكد من إضافة هذا السطر

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'permission_id', 'status', 'status_updated_at','created_user', 'updated_user'];

    // تحديث `status_updated_at` تلقائيًا عند تغيير `status`
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($rolePermission) {
            if ($rolePermission->isDirty('status')) {
                $rolePermission->status_updated_at = now();
            }
        });
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

   

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    
}
