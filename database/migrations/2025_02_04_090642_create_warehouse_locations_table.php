<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            // معرف المستودع (مفتاح أجنبي يشير إلى جدول warehouses)
            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->onDelete('cascade');
            // معرف منطقة التخزين (مفتاح أجنبي يشير إلى جدول warehouse_storage_areas)
            $table->foreignId('storage_area_id')
                  ->constrained('warehouse_storage_areas')
                  ->onDelete('cascade');
            $table->string('aisle');           // رقم الممر
            $table->string('rack');            // رقم الرف
            $table->string('shelf');           // رقم الرف الفرعي
            $table->string('position');        // الموقع على الرف
            $table->string('barcode')->unique(); // باركود الموقع (فريد)
            $table->boolean('is_occupied')->default(false); // هل الموقع مشغول
            $table->text('notes')->nullable(); // ملاحظات (اختياري)
            $table->timestamps();              // تواريخ الإنشاء والتحديث
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_locations');
    }
};
