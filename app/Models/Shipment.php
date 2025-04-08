<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  // إضافة هذا السطر إذا كنت تستخدم الفاكتوري

class Shipment extends Model
{
    use HasFactory;  // استخدام الـ HasFactory إذا كنت تستخدم الفاكتوري لإنشاء البيانات

    protected $fillable = [
        'shipment_number', 
        'shipment_date',
        'status',
        'product_id',
        'quantity'
    ];

    // علاقة مع منتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}

