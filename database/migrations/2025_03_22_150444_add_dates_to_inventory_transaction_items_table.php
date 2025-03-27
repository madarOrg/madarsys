<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
        public function up(): void
        {
            Schema::table('inventory_transaction_items', function (Blueprint $table) {
                $table->timestamp('production_date')->nullable();
                $table->timestamp('expiration_date')->nullable();
                $table->foreignId('source_warehouse_id')->nullable();

            });
        }
    
        public function down(): void
        {
            Schema::table('inventory_transaction_items', function (Blueprint $table) {
            });
                $table->dropColumn(['production_date', 'expiration_date','source_warehouse_id']);
            });
        }
    };
    
