<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityAndCurrentOccupancyToZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zones', function (Blueprint $table) {
            // $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->integer('capacity')->default(0); // إضافة حقل السعة الكلية
            $table->integer('current_occupancy')->default(0); // إضافة حقل عدد الوحدات المخزنة حاليًا
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'current_occupancy']);
        });
    }
}
