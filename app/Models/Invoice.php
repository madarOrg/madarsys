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
        'discount_amount', 'discount_percentage', 'type'
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
}