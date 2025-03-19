<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_id')->nullable()->after('type'); // Add inventory_id column
            $table->unsignedBigInteger('warehouse_id')->nullable()->after('inventory_id'); // Add warehouse_id column
            $table->unsignedBigInteger('currency_id')->nullable()->after('warehouse_id'); // Add currency_id column
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('currency_id'); // Add exchange_rate column
            $table->unsignedBigInteger('department_id')->nullable()->after('exchange_rate'); // Add department_id column
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'inventory_id',
                'warehouse_id',
                'currency_id',
                'exchange_rate',
                'department_id'
            ]);
        });
    }
};
