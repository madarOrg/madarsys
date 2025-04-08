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
        'payment_type_id',
        // 'inventory_transaction_id',
    ];
 
    // علاقة مع تفاصيل الطلب
    // public function orderDetails()
    // {
    //     return $this->hasMany(OrderDetail::class);
    // }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function order_details() 
    {
        return $this->hasmany(OrderDetail::class);
    }

    // علاقة مع الفاتورة
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}

