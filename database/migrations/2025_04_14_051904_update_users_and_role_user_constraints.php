<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تعديل جدول المستخدمين (users) لمنع الحذف
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']); // إذا كانت هناك علاقة مع الفرع وتريد التعديل عليها

            // إضافة قيد جديد لمنع الحذف (المستخدمين مرتبطين بالدور)
            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onDelete('restrict');  // يمنع الحذف إذا كان هناك ارتباط بالفرع
        });

        // تعديل جدول role_user لمنع حذف المستخدمين إذا كانوا مرتبطين بدور
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);  // إذا كنت تستخدم قيد الحذف التلقائي
            $table->dropForeign(['role_id']);

            // منع الحذف إذا كان هناك ارتباط مع المستخدم
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict');  // لا يمكن حذف المستخدم إذا كان مرتبطًا بدور

            // إضافة القيد لمنع الحذف عند وجود ارتباط مع دور
            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('restrict');  // لا يمكن حذف الدور إذا كان مرتبطًا بمستخدم
        });
    }

    public function down(): void
    {
        // العودة إلى القيم السابقة إذا تم التراجع عن التغييرات
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onDelete('set null');
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['role_id']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('cascade');
        });
    }
};
