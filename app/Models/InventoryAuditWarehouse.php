<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;
use App\Traits\HasUser;

class InventoryAuditWarehouse extends Model
{
    use HasBranch, HasUser;

    protected $fillable = [
        'inventory_audit_id',
        'warehouse_id',
        'branch_id',
        'created_user',
        'updated_user'
    ];

    /**
     * العلاقة مع الجرد.
     */
    public function audit()
    {
        return $this->belongsTo(InventoryAudit::class, 'inventory_audit_id');
    }

    /**
     * العلاقة مع المستودع.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
