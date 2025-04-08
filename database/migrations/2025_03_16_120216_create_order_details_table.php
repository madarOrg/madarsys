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
        // Schema::create('order_details', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('order_id'); // معرف الطلب
        //     $table->unsignedBigInteger('product_id'); // معرف المنتج
        //     $table->integer('quantity'); // الكمية المطلوبة
        //     $table->decimal('price', 8, 2); // السعر
        //     $table->decimal('total_price', 8, 2); // السعر الكلي
        //     $table->timestamps();
    
        //     $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        // });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('product_id')->constrained('products');;
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
