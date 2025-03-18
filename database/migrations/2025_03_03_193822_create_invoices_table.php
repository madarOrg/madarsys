<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي
            $table->string('invoice_code')->nullable(); // رقم الفاتورة
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade'); // علاقة مع الشركاء
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->onDelete('cascade'); // نوع الدفع
            $table->date('invoice_date'); // تاريخ الفاتورة
            $table->decimal('total_amount', 10, 2); // المجموع الكلي للفاتورة
            $table->string('check_number')->nullable(); // رقم الشيك
            $table->unsignedTinyInteger('type')->default(value: 1)->comment('1 for sale, 2 for purchase invoice'); // نوع الفاتورة (1 للبيع، 2 للشراء)
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade')->comment('رقم الفرع'); // رقم الفرع (علاقة مع جدول الفروع)
            $table->foreignId('created_user')->nullable()->constrained('users')->comment('رقم المستخدم الذي قام بإضافة هذا السجل'); // المستخدم الذي أنشأ الفاتورة
            $table->foreignId('updated_user')->nullable()->constrained('users')->comment('رقم المستخدم الذي قام بآخر تحديث لهذا السجل'); // المستخدم الذي قام بتحديث الفاتورة
            $table->decimal('discount_amount', 10, 2)->nullable()->default(0); // مبلغ الخصم
            $table->integer('discount_type')->nullable();
            $table->decimal('discount_percentage')->nullable();
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
