<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->tinyInteger('effect')->nullable()->after('transaction_type_id')
                ->comment('تحديد تأثير العملية على المخزون: + زيادة، - نقصان، 0 محايدة');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('effect');
        });
    }
};
