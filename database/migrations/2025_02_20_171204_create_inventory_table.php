<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // ربط مع جدول warehouses
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');   // ربط مع جدول products
            $table->integer('quantity');          // الكمية الحالية
            $table->decimal('unit_price', 10, 2); // السعر للوحدة
            $table->decimal('total_value', 15, 2); // الرصيد التراكمي (الكمية * السعر)
            $table->timestamps();                 // الحقول للتواريخ (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory');
    }
}
