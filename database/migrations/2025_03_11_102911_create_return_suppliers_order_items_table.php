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
        Schema::create('return_suppliers_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_supplier_order_id')->constrained('return_suppliers_orders')->onDelete('cascade'); // علاقة مع تفاصيل طلبات الموردين للارجاع
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // علاقة مع الشركاء
            $table->integer('quantity')->default(1);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_suppliers_order_items');
    }
};
