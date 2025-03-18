<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
// تعيين `id` كمفتاح رئيسي من نوع UUID
protected $keyType = 'string';
public $incrementing = false; // لأننا لا نستخدم عداد تلقائي

    protected $fillable = [
        'type', 
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at',
    ];

    // دالة العلاقة للمستخدم (المستخدِم الذي سيتم إشعارهم)
    public function notifiable()
    {
        return $this->morphTo();
    }

   
}
