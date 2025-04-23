<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign('inventory_transactions_inventory_request_id_foreign');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreign('inventory_request_id')
                  ->references('id')
                  ->on('inventory_requests')
                  ->onDelete('set null'); // أو onDelete('cascade') حسب السابق
        });
    }
};


