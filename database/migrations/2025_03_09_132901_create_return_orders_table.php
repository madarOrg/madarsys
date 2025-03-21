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
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique(); // رقم المرتجع
            $table->unsignedBigInteger('customer_id'); //  العميل
            $table->text('return_reason')->nullable(); // سبب الإرجاع
            $table->timestamp('return_date')->useCurrent(); // تاريخ الإرجاع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_orders');
    }
};

