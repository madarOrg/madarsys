<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndReturnNumberToReturnSuppliersOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('return_suppliers_orders', function (Blueprint $table) {
            $table->enum('status', ['قيد المراجعة', 'قيد التوصيل', 'تم الاستلام'])->default('قيد المراجعة')->after('id');
            $table->string('return_number')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('return_suppliers_orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'return_number']);
        });
    }
}
