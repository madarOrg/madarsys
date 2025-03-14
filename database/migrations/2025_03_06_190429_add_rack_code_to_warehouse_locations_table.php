<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRackCodeToWarehouseLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('warehouse_locations', function (Blueprint $table) {
            // إضافة حقل كود الرف
            $table->string('rack_code')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('warehouse_locations', function (Blueprint $table) {
            // حذف حقل كود الرف في حال التراجع عن الميجرشن
            $table->dropColumn('rack_code');
        });
    }
}
