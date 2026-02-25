<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('production_date')->nullable()->after('id');
            $table->date('expiration_date')->nullable()->after('production_date');
            $table->unsignedBigInteger('payment_type_id')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('production_date');
            $table->dropColumn('expiration_date');
            // ملاحظة: لا يمكن التراجع عن تغيير القيمة الافتراضية إلا إذا كانت القيمة الأصلية معروفة، لذا يمكن حذف هذا السطر أو إعادة القيمة كما كانت:
            $table->unsignedBigInteger('payment_type_id')->default(null)->change();
        });
    }
};

