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
        Schema::create('module_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->string('name'); // اسم الإجراء (مثال: "إنشاء مستودع")
            $table->string('action_key'); // مفتاح الإجراء (مثال: "create")
            $table->string('route'); 
            $table->string('icon'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_actions');
    }
};
