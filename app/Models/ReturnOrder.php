<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_number',
        'customer_id',
        'return_reason',
        'return_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Partner::class, 'customer_id');
    }
    
    public function items()
    {
        return $this->hasMany(ReturnOrderItem::class);

    }
}
