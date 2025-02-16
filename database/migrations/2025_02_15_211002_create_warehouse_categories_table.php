<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
       
        Schema::create('warehouse_category_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_categories');
    }
};
