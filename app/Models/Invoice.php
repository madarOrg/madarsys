<?php

namespace App\Models; // تحديد المسار (namespace) لهذا الموديل داخل التطبيق

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory; // استخدام الـ Trait لإنشاء سجلات وهمية (Factories) أثناء الاختبار

    // تحديد الحقول القابلة للتعبئة بشكل مباشر باستخدام التخصيص الجماعي (Mass Assignment)
    protected $fillable = ['customer_id', 'invoice_date', 'total_amount', 'payment_type_id'];
    /**
     * علاقة بين الفاتورة والعميل (الشريك) بحيث تنتمي الفاتورة إلى شريك معين.
     * يتم الربط عن طريق المفتاح الأجنبي `customer_id` الذي يشير إلى جدول `partners`.
     */
    public function customer()
    {
        return $this->belongsTo(Partner::class, 'customer_id');
    }

    /**
     * علاقة بين الفاتورة وعناصر الفاتورة، حيث تحتوي الفاتورة على عدة عناصر.
     * يتم الربط عن طريق المفتاح الأجنبي `invoice_id` في جدول `invoice_items`.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
