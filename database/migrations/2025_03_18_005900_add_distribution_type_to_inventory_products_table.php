<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistributionTypeToInventoryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_products', 'distribution_type')) {
                $table->tinyInteger('distribution_type')
                      ->default(1)
                      ->comment('نوع التوزيع: 1 = ادخال / -1 = اخراج');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_products', 'distribution_type')) {
                $table->dropColumn('distribution_type');
            }
        });
    }
}
