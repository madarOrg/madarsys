<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnOrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'return_order_id',
        'product_id',
        'quantity',
        'status',
        'Is_Send',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function returnOrder()
    {
        return $this->belongsTo(ReturnOrder::class, 'return_order_id'); // تأكد من أن المفتاح الأجنبي هو 'return_order_id'
    }
        
}
