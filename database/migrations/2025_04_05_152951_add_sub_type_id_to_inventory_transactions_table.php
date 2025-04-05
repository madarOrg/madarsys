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
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_type_id')->nullable()->after('transaction_type_id')->comment('النوع الفرعي للعملية');
            
            $table->foreign('sub_type_id')
                  ->references('id')
                  ->on('inventory_transaction_subtypes')
                  ->onUpdate('no action')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['sub_type_id']);
            $table->dropColumn('sub_type_id');
        });
    }
};
