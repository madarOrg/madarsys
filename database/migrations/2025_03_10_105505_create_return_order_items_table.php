<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_order_id')->constrained('return_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('status', [
                'قبول الإرجاع',
                'إرجاع للمخزون',
                'إرسال للصيانة',
                'تصنيف كمنتج تالف',
                'منتهي الصلاحية'
            ])->default('تصنيف كمنتج تالف');
            $table->integer('Is_Send')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_order_items');
    }
};
