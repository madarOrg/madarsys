<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory; 

    protected $fillable = [
        'invoice_code', 'partner_id', 'invoice_date', 'payment_type_id', 
        'branch_id', 'total_amount', 'check_number', 'discount_type', 
        'discount_amount', 'discount_percentage', 'type', 
        'inventory_id', 'warehouse_id', 'currency_id', 
        'exchange_rate', 'department_id'
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
}