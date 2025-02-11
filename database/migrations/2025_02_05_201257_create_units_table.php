<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم الوحدة مثل (حبة، كرتون، كيلوغرام)
            $table->foreignId('parent_unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->decimal('conversion_factor', 10, 4)->nullable(); // معامل التحويل بالنسبة للوحدة الأصلية
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->comment('الفرع التابع له الطلب');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
