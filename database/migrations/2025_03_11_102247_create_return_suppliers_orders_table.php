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
        Schema::create('return_suppliers_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('partners')->onDelete('cascade'); // علاقة مع الشركاء
            $table->enum('status', [
                'قيد المراجعة',
                'قيد التوصيل',
                'تم الاستلام'
            ])->default('قيد المراجعة');
            $table->string('return_reason');
            $table->date('return_date')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_suppliers_orders');
    }
};
