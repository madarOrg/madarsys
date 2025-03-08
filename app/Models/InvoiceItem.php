<?php

namespace App\Models; // تحديد المسار (namespace) لهذا الموديل داخل التطبيق

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory; // استخدام الـ Trait لإنشاء بيانات وهمية أثناء الاختبار

    // تحديد الحقول القابلة للتعبئة بشكل مباشر باستخدام التخصيص الجماعي (Mass Assignment)
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'price', 'subtotal'];

    /**
     * علاقة بين عنصر الفاتورة والفاتورة الرئيسية.
     * كل عنصر ينتمي إلى فاتورة واحدة فقط.
     * يتم الربط باستخدام المفتاح الأجنبي `invoice_id` الذي يشير إلى جدول `invoices`.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * علاقة بين عنصر الفاتورة والمنتج.
     * كل عنصر في الفاتورة يمثل منتجًا معينًا.
     * يتم الربط باستخدام المفتاح الأجنبي `product_id` الذي يشير إلى جدول `products`.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
