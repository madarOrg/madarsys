<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number');  // رقم الشحنة
            $table->date('shipment_date');  // تاريخ الشحنة
            $table->enum('status', ['pending', 'shipped', 'delivered']);  // حالة الشحنة
            $table->foreignId('product_id')->constrained('products');  // رابطة مع جدول المنتجات
            $table->timestamps();  // تاريخ الإنشاء والتعديل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');  // حذف جدول الشحنات إذا تم التراجع عن التغيير
    }
};

