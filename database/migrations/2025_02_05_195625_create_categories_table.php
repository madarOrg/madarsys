<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // مفتاح رئيسي Auto Increment
            $table->string('name')->unique(); // اسم التصنيف ويكون فريدًا
            $table->text('description')->nullable(); // وصف التصنيف (اختياري)
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
