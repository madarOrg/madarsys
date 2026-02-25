<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;
use App\Traits\HasUser;

class Order extends Model
{
    use 
    HasBranch,
    HasUser,
    HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'type',
        'status',
        'branch_id',
        'payment_type_id',
        'partner_id',
        'purchase_order_number',
        'is_printed',
         'inventory_transaction_id',
         'warehouse_id'
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
        return $this->hasMany(OrderDetail::class,'order_id');
    }

    // علاقة مع الفاتورة
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
    
    // علاقة مع المورد/الشريك
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    
    // علاقة مع الفواتير
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    // علاقة مع أوامر الشراء
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    
    // علاقة مع أوامر الصرف (البيع)
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
   public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

}
