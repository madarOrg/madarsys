<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryUpdateErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_update_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_transaction_item_id');
            $table->foreignId('product_id');
            $table->foreignId('warehouse_id');
            $table->integer('quantity');
            $table->string('error_message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_update_errors');
    }
}
