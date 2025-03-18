<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'type',
        'status',
        'branch_id',
    ];

    // علاقة مع تفاصيل الطلب
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    
    // علاقة مع الفاتورة
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}

