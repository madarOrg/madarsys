<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade'); // علاقة مع الفواتير
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // علاقة مع المنتجات
            $table->integer('quantity'); // الكمية المباعة من المنتج
            $table->decimal('price', 10, 2); // سعر المنتج
            $table->decimal('subtotal', 10, 2); // المجموع الجزئي للعنصر
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
