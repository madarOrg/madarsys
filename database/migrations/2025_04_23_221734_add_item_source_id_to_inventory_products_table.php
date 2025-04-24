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
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->unsignedBigInteger('item_source_id')->nullable()->after('id');
    
            $table->foreign('item_source_id')
                  ->references('id')
                  ->on('inventory_transaction_items')
                  ->onDelete('set null');
        });
    }
    
    public function down(): void
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->dropForeign(['item_source_id']);
            $table->dropColumn('item_source_id');
        });
    }
    };
