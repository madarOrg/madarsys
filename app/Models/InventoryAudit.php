<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    protected $fillable = [
        'inventory_code',
        'inventory_type',
        'start_date',
        'end_date',
        'status',
        'expected_products_count',
        'counted_products_count',
        'notes',
        'created_by'
    ];

    /**
     * العلاقة مع المستخدمين المسؤولين عن الجرد.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'inventory_audit_users');
    }

    /**
     * العلاقة مع المستودعات المستهدفة للجرد.
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'inventory_audit_warehouses');
    }
}
