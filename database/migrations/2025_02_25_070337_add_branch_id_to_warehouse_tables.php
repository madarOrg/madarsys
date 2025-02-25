<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('warehouse_locations', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('id')->nullable()->constrained('branches')->nullOnDelete();
        });

        Schema::table('warehouse_storage_areas', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('id')->nullable()->constrained('branches')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('warehouse_locations', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('warehouse_storage_areas', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
