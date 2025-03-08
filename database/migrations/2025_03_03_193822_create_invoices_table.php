<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إنشاء جدول الفواتير
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي
            $table->foreignId('customer_id')->constrained('partners')->onDelete('cascade'); // علاقة مع الشركاء
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->onDelete('set null'); // نوع الدفع
            $table->date('invoice_date'); // تاريخ الفاتورة
            $table->decimal('total_amount', 10, 2); // المجموع الكلي للفاتورة
            $table->unsignedBigInteger('created_user')->nullable()->comment('رقم المستخدم الذي قام بإضافة هذا السجل'); // المستخدم الذي أنشأ الفاتورة
            $table->unsignedBigInteger('updated_user')->nullable()->comment('رقم المستخدم الذي قام بآخر تحديث لهذا السجل'); // المستخدم الذي قام بتحديث الفاتورة
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    /**
     * حذف الجدول عند التراجع عن المهاجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
