<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransactionDateTypeInInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            // تغيير نوع العمود إلى datetime
            $table->timestamp('transaction_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            // إعادة العمود إلى نوع date
            $table->date('transaction_date')->change();
        });
    }
}
