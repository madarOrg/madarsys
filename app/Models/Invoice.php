<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};
class Invoice extends Model
{
    use HasBranch,
    HasUser,
    HasFactory; 

    protected $fillable = [
        'invoice_code', 'partner_id', 'invoice_date', 'payment_type_id', 
        'branch_id', 'total_amount', 'check_number', 'discount_type', 
        'discount_amount', 'discount_percentage', 'type', 
        'inventory_id', 'warehouse_id', 'currency_id', 
        'exchange_rate', 'department_id','inventory_transaction_id',
        'production_date',
        'expiration_date',
        'created_user', 'updated_user',
        'order_id', 'purchase_order_id', 'sales_order_id',
    ];
    
   
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id'); 
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id'); 
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class);
    }
    
    // علاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    // علاقة مع أمر الشراء
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    
    // علاقة مع أمر الصرف
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
}