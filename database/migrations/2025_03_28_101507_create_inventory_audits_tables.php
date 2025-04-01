<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAuditsTables extends Migration
{
    public function up()
    {
        // جدول عمليات الجرد
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_code')->unique(); // كود الجرد الفريد
            $table->unsignedTinyInteger('inventory_type'); // نوع الجرد (1=دوري, 2=مفاجئ, 3=سنوي, 4=شهري)
            $table->dateTime('start_date')->nullable(); // تاريخ بدء الجرد
            $table->dateTime('end_date')->nullable(); // تاريخ انتهاء الجرد
            $table->unsignedTinyInteger('status')->default(1); // حالة الجرد (1=معلق, 2=جاري, 3=مكتمل, 4=متأخر)
            $table->integer('expected_products_count')->nullable(); // عدد المنتجات المتوقع جردها
            $table->integer('counted_products_count')->nullable(); // عدد المنتجات التي تم جردها
            $table->text('notes')->nullable(); // ملاحظات حول الجرد
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate(); // ربط الجرد بالفرع
            $table->foreignId('created_user')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate(); // المستخدم الذي أنشأ الجرد
            $table->foreignId('updated_user')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate(); // المستخدم الذي قام بالتحديث
            $table->timestamps();
        });

        // جدول المستخدمين المسؤولين عن الجرد
        Schema::create('inventory_audit_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_audit_id')->constrained('inventory_audits')->cascadeOnDelete()->cascadeOnUpdate(); // ربط الجرد
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate(); // المستخدم المسؤول عن الجرد
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate(); // ربط الجرد بالفرع
            $table->foreignId('created_user')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate(); // المستخدم الذي أدخل السجل
            $table->foreignId('updated_user')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate(); // المستخدم الذي قام بالتحديث
            $table->unsignedTinyInteger('operation_type')->default(1); // نوع العملية: 1 = جرد، 2 = تسوية
            $table->timestamps();
        });

        // جدول المستودعات المرتبطة بالجرد
        Schema::create('inventory_audit_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_audit_id')->constrained('inventory_audits')->cascadeOnDelete()->cascadeOnUpdate(); // ربط الجرد
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate(); // ربط بالمستودع
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate(); // ربط الجرد بالفرع
            $table->foreignId('created_user')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate(); // المستخدم الذي أدخل السجل
            $table->foreignId('updated_user')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate(); // المستخدم الذي قام بالتحديث
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_audit_warehouses');
        Schema::dropIfExists('inventory_audit_users');
        Schema::dropIfExists('inventory_audits');
    }
}
