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
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('storage_area_id')->constrained('warehouse_storage_areas')->onDelete('cascade');
            $table->string('aisle');           // رقم الممر
            $table->string('rack');            // رقم الرف
            $table->string('shelf');           // رقم الرف الفرعي
            $table->string('position');        // الموقع على الرف
            $table->string('barcode')->unique(); // باركود الموقع
            $table->boolean('is_occupied')->default(false); // هل الموقع مشغول
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_locations');
    }
};
