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
      
        //     Schema::create('orders', function (Blueprint $table) {
        //         $table->id();
        //         $table->enum('type', ['buy', 'sell']); // نوع الطلب (شراء أو بيع)
        //         $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled']); // حالة الطلب
        //         $table->unsignedBigInteger('branch_id'); // رقم الفرع المرتبط بالطلب
        //         $table->timestamps();
                
        
        //         $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        // }); 
        // Schema::create('orders', function (Blueprint $table) {
        //     $table->id();
        //     $table->enum('type', ['buy', 'sell']);
        //     $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
        //     // $table->foreignId('branch_id')->constrained();
        //     $table->timestamps();
        // });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['buy', 'sell']);
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade')->comment('رقم الفرع'); // رقم الفرع (علاقة مع جدول الفروع)
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->onDelete('cascade'); // نوع الدفع

            // $table->foreignId('branch_id')->constrained();
            $table->foreignId('created_user')->nullable()->constrained('users')->comment('رقم المستخدم الذي قام بإضافة هذا السجل'); // المستخدم الذي أنشأ الفاتورة
            $table->foreignId('updated_user')->nullable()->constrained('users')->comment('رقم المستخدم الذي قام بآخر تحديث لهذا السجل'); // المستخدم الذي قام بتحديث الفاتورة

            $table->timestamps();
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

