<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch, HasUser;

class InventoryTransaction extends Model
{
    use HasFactory, HasUser, HasBranch;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity', 
        'price', 
        'transaction_type', 
        'transaction_type_id', 
        'effect', 
        'transaction_date', 
        'reference', 
        'partner_id', 
        'department_id', 
        'warehouse_id', 
        'secondary_warehouse_id', 
        'notes', 
        'branch_id', 
        'inventory_request_id', 
        'created_user', 
        'updated_user', 
        'status',
    ];

    // التعامل مع التاريخ
    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    // علاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // علاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // علاقة مع نوع المعاملة (TransactionType)
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    // علاقة مع المخزن الأول (Warehouse)
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // علاقة مع الشريك (Partner)
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    // علاقة مع القسم (Department)
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // علاقة مع المخزن الثانوي (Secondary Warehouse)
    public function secondaryWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'secondary_warehouse_id');
    }

    // علاقة مع الحركات المخزنية (InventoryTransactionItem)
    public function items()
    {
        return $this->hasMany(InventoryTransactionItem::class);
    }
}

