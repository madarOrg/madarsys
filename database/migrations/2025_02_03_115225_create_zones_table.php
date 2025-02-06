<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم المنطقة
            $table->string('code')->unique(); // رمز المنطقة
            $table->text('description')->nullable(); // وصف المنطقة
            $table->timestamps();
        });
    }

public function down()
    {
        Schema::dropIfExists('zones');
    }
};

