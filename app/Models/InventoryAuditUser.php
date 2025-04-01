<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;
use App\Traits\HasUser;

class InventoryAuditUser extends Model
{
    use HasBranch, HasUser;

    protected $fillable = [
        'inventory_audit_id',
        'user_id',
        'branch_id',
        'created_user',
        'updated_user',
        'operation_type', // إضافة الحقل إلى الـfillable

    ];

    /**
     * العلاقة مع الجرد.
     */
    public function audit()
    {
        return $this->belongsTo(InventoryAudit::class, 'inventory_audit_id');
    }

    /**
     * العلاقة مع المستخدم.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
