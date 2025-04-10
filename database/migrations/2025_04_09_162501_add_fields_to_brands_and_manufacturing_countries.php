<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBrandsAndManufacturingCountries extends Migration
{
    /**
     * تشغيل الهجرة.
     *
     * @return void
     */
    public function up()
    {
        // إضافة الحقول إلى جدول "brands"
        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedBigInteger('created_user')->nullable()->after('description')->comment('معرف المستخدم الذي أنشأ السجل');
            $table->unsignedBigInteger('updated_user')->nullable()->after('created_user')->comment('معرف المستخدم الذي قام بتحديث السجل');
            $table->unsignedBigInteger('branch_id')->nullable()->after('updated_user')->comment('معرف الفرع المرتبط');
        });

        // إضافة الحقول إلى جدول "manufacturing_countries"
        Schema::table('manufacturing_countries', function (Blueprint $table) {
            $table->unsignedBigInteger('created_user')->nullable()->after('description')->comment('معرف المستخدم الذي أنشأ السجل');
            $table->unsignedBigInteger('updated_user')->nullable()->after('created_user')->comment('معرف المستخدم الذي قام بتحديث السجل');
            $table->unsignedBigInteger('branch_id')->nullable()->after('updated_user')->comment('معرف الفرع المرتبط');

        });
    }

    /**
     * التراجع عن الهجرة.
     *
     * @return void
     */
    public function down()
    {
        // حذف الحقول من جدول "brands"
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['created_user', 'updated_user', 'branch_id']);
        });

        // حذف الحقول من جدول "manufacturing_countries"
        Schema::table('manufacturing_countries', function (Blueprint $table) {
            $table->dropColumn(['created_user', 'updated_user', 'branch_id']);
        });
    }
}
