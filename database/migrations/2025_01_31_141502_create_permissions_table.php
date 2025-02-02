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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); 
            $table->foreignId('module_id')->constrained();
            $table->foreignId('module_action_id')->constrained();
            $table->string('permission_key')->unique(); // مثال: "warehouses.create"
            $table->enum('scope_level', ['company', 'branch', 'warehouse'])->default('company'); // نطاق الصلاحية
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
