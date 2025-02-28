<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvertedPriceToInventoryTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            // إضافة عمود converted_price بعد عمود total_value
            $table->decimal('converted_price', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            $table->dropColumn('converted_price');
        });
    }
}
