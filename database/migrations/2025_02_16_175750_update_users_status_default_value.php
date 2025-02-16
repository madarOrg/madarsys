<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ التعديلات على الجدول.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إنشاء حقل جديد `status_new` من نوع `integer` بقيمة افتراضية `1`
            $table->integer('status_new')->default(1);
        });

        // تحديث القيم النصية إلى أرقام
        DB::table('users')->where('status', 'active')->update(['status_new' => 1]);
        DB::table('users')->where('status', 'inactive')->update(['status_new' => 0]);

        Schema::table('users', function (Blueprint $table) {
            // حذف الحقل `status` القديم
            $table->dropColumn('status');
            
            // إعادة تسمية `status_new` إلى `status`
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة حقل `status` مرة أخرى كـ string
            $table->string('status')->default('active');
        });

        // إعادة القيم الرقمية إلى نصوص
        DB::table('users')->where('status', 1)->update(['status' => 'active']);
        DB::table('users')->where('status', 0)->update(['status' => 'inactive']);

        Schema::table('users', function (Blueprint $table) {
            // حذف الحقل الجديد `status_new`
            $table->dropColumn('status');
        });
    }
};
