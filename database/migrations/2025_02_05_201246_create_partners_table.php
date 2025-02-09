<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id(); // معرف الشريك
            $table->string('name'); // اسم الشريك
            $table->foreignId('type')->constrained('partner_types')->onDelete('cascade'); // نوع الشريك (مرتبط بجدول partner_types)
            $table->string('contact_person')->nullable(); // اسم الشخص المسؤول
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('email')->unique()->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->string('tax_number')->unique()->nullable(); // الرقم الضريبي (للموردين والعملاء)
            $table->boolean('is_active')->default(true); // حالة التفعيل
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
