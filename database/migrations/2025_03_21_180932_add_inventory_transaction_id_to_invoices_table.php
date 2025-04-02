<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_transaction_id')->nullable()->after('warehouse_id');
            $table->foreign('inventory_transaction_id')->references('id')->on('inventory_transactions')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['inventory_transaction_id']);
            $table->dropColumn('inventory_transaction_id');
        });
    }
};