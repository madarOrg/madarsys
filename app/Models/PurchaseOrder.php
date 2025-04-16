<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_id',
        'partner_id',
        'status',
        'issue_date',
        'expected_delivery_date',
        'notes',
        'is_printed',
        'created_user',
        'updated_user',
    ];

    // علاقة مع الطلب الأصلي
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // علاقة مع المورد/الشريك
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    // علاقة مع الفواتير
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'purchase_order_id');
    }

    // علاقة مع المستخدم الذي أنشأ السجل
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    // علاقة مع المستخدم الذي قام بتحديث السجل
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_user');
    }
}
