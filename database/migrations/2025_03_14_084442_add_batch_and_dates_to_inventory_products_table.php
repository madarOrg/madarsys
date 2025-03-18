<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->string('batch_number')->nullable()->after('id');
            $table->date('production_date')->nullable()->after('batch_number');
            $table->date('expiration_date')->nullable()->after('production_date');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->dropColumn(['batch_number', 'production_date', 'expiration_date']);
        });
    }
};
