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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'completed', 'canceled'])->default('pending');
            $table->date('issue_date');
            $table->date('expected_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_printed')->default(false);
            $table->foreignId('created_user')->nullable()->constrained('users')->comment('رقم المستخدم الذي قام بإضافة هذا السجل');
            $table->foreignId('updated_user')->nullable()->constrained('users')->comment('رقم المستخدم الذي قام بآخر تحديث لهذا السجل');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
