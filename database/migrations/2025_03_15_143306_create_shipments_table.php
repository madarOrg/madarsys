<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // إنشاء جدول purchase_invoices
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('partners')->onDelete('cascade');
            $table->date('invoice_date');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        // إضافة عمود prefix إلى جدول orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('prefix9')->nullable(); // إضافة عمود prefix9
        });
    }

    public function down()
    {
        // إزالة عمود prefix من جدول orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('prefix9');
        });

        // حذف جدول purchase_invoices
        Schema::dropIfExists('purchase_invoices');
    }
};

