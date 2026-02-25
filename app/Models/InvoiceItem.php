<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory; 

    
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'price', 'subtotal', 'unit_id',   'production_date',
    'expiration_date',]; 


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id'); 
    }
    
}