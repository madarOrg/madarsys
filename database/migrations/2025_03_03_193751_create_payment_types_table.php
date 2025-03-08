<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('اسم نوع الدفع');
            $table->unsignedBigInteger('created_user')->nullable()->comment('رقم المستخدم الذي قام بإضافة هذا السجل');
            $table->unsignedBigInteger('updated_user')->nullable()->comment('رقم المستخدم الذي قام بآخر تحديث لهذا السجل');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_types');
    }
};
