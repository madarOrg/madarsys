<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('unit_product_id')->nullable();
            $table->decimal('converted_quantity', 10, 4)->nullable();
            $table->decimal('price', 20, 6)->nullable(); // إجمالي السعر


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['unit_product_id']);
            $table->dropColumn(['unit_id', 'unit_product_id', 'converted_quantity','price']);
        });
    }
};
