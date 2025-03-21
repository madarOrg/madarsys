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
        Schema::create('products', function (Blueprint $table) {
            
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');  // اسم المنتج
                $table->decimal('price', 8, 2);  // سعر المنتج
                $table->integer('quantity');  // الكمية في المخزون
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

{
    
}
