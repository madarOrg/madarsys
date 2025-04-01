<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchNumberToInventoryTransactionItemsTable extends Migration
{
    public function up()
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            // إضافة رقم الدفعة من جدول inventory_products
            $table->string('batch_number')
                ->nullable()
                ->comment('رقم الدفعة من جدول inventory_products');
        });
    }

    public function down()
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            $table->dropColumn('batch_number');
        });
    }
}
