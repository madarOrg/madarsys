<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnSuppliers extends Model
{
    //

      // الحقول القابلة للتعبئة الجماعية
    protected $fillable = [
        'return_order_id',
        'product_id',
        'supplier_id',
        'quantity',
        'status',
        'Is_Send',
        'return_reason',
    ];

    // تحويل أنواع الحقول
    protected $casts = [
        'Is_Send' => 'boolean', // تحويل القيمة إلى بوليان (صح/خطأ)
    ];

    // العلاقة مع طلب الإرجاع (ذاتية)
    public function returnOrder()
    {
        return $this->belongsTo(ReturnSupplier::class, 'return_order_id');
    }

    // العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // العلاقة مع المورد (من جدول الشركاء)
    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }

    // الحصول على الحالات الممكنة للإرجاع
    public static function getStatuses()
    {
        return [
            'قبول الإرجاع',
            'إرجاع للمخزون',
            'إرسال للصيانة',
            'تصنيف كمنتج تالف',
            'منتهي الصلاحية'
        ];
    }

}
