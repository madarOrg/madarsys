<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventoryRequestIdToInventoryTransactions extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreignId('inventory_request_id')->nullable()->constrained('inventory_requests')->onDelete('set null')
                ->comment('ربط بالطلب المخزني');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['inventory_request_id']);
            $table->dropColumn('inventory_request_id');
        });
    }
}
