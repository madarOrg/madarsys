<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Company name
            $table->string('logo')->nullable(); // Company logo

            // الحقول الجديدة
            $table->string('phone_number', 20)->unique(); // رقم الهاتف
            $table->string('email')->nullable();       // البريد الإلكتروني
            $table->string('address')->nullable();     // العنوان
            $table->text('additional_info')->nullable(); // معلومات إضافية

            $table->json('settings')->nullable(); // JSON settings
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
