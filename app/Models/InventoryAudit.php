<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;
use App\Traits\HasUser;

class InventoryAudit extends Model
{
    use HasBranch, HasUser;

    protected $fillable = [
        'inventory_code',
        'inventory_type',
        'start_date',
        'end_date',
        'status',
        'expected_products_count',
        'counted_products_count',
        'notes',
        'branch_id',
        'created_user',
        'updated_user'
    ];
    public static function generateAuditCode()
    {
        $date = now()->format('Ymd'); // تاريخ اليوم بصيغة YYYYMMDD
        $latestAudit = self::latest()->first(); // آخر جرد مضاف
        $nextId = $latestAudit ? $latestAudit->id + 1 : 1; // تحديد الـ ID التالي

        return "audit-{$date}-{$nextId}";
    }

    /**
     * العلاقة مع المستخدمين المسؤولين عن الجرد (عدة مستخدمين).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'inventory_audit_users')
            ->withPivot('created_user', 'updated_user', 'branch_id');
    }

    /**
     * العلاقة مع المستودعات المستهدفة للجرد (عدة مستودعات).
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'inventory_audit_warehouses')
            ->withPivot('created_user', 'updated_user', 'branch_id');
    }
    
public function subType()
{
    return $this->belongsTo(InventoryTransactionSubtype::class, 'inventory_type','id');
}

}
