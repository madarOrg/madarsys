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
      
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['buy', 'sell']); // نوع الطلب (شراء أو بيع)
                $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled']); // حالة الطلب
                $table->unsignedBigInteger('branch_id'); // رقم الفرع المرتبط بالطلب
                $table->timestamps();
        
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

