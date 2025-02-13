<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitProductIdToInventoryTransactions extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
                
                $table->foreignId('unit_product_id')->nullable()->constrained('units')->onDelete('set null')
                ->comment('ربط  بالوحدة الاساس للمنتج');;

        });
    }

    
}
