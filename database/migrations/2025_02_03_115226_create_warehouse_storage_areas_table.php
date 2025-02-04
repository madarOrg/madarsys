<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouse_storage_areas', function (Blueprint $table) {
            $table->id(); // area_id
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('area_name');
            $table->string('area_type');
            $table->integer('capacity');
            $table->integer('current_occupancy')->default(0);
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null');
            $table->string('storage_conditions')->nullable();
            $table->timestamps(); // last_updated
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_storage_areas');
    }
};

