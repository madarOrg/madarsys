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
        Schema::create('return_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_order_id')->constrained('return_suppliers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('partners')->onDelete('cascade'); // علاقة مع الشركاء
            $table->integer('quantity');
            $table->string('status')->default('معلق');
            $table->integer('Is_Send')->default(0);
            $table->string('return_reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_suppliers');
    }
};
