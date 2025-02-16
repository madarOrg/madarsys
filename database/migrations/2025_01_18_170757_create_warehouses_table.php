<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('warehouses', function (Blueprint $table) {
        $table->id(); // المفتاح الأساسي  
        $table->string('name'); // اسم المستودع  
        $table->string('code')->unique(); // كود المستودع للتمييز  
        $table->string('address')->nullable(); // عنوان المستودع  
        $table->text('contact_info')->nullable(); // معلومات الاتصال  
        $table->foreignId('branch_id') // مفتاح أجنبي لجدول الفروع  
              ->constrained('branches')
              ->onDelete('cascade'); // حذف المستودع عند حذف الفرع  
        $table->foreignId('supervisor_id') // مفتاح أجنبي لجدول المستخدمين (المشرف)  
              ->nullable() // اختياري: في حال لم يكن هناك مشرف معين  
              ->constrained('users')
              ->onDelete('set null'); // عند حذف المشرف يتم تعيين القيمة إلى null  
        $table->decimal('latitude', 10, 7)->nullable(); // خط العرض  
        $table->decimal('longitude', 10, 7)->nullable(); // خط الطول  
        $table->float('area', 8, 2)->nullable(); // مساحة المستودع بالمتر المربع  
        $table->float('capacity')->nullable(); // سعة التخزين للمستودع  
        $table->boolean('is_smart')->default(false); // هل المستودع ذكي؟  
        $table->boolean('has_security_system')->default(false); // هل يوجد نظام أمني؟  
        $table->boolean('has_cctv')->default(false); // هل يوجد كاميرات مراقبة؟  
        $table->boolean('is_integrated_with_wms')->default(false); // هل هو مدمج مع نظام إدارة المستودعات؟  
        $table->timestamp('last_maintenance')->nullable(); // تاريخ آخر صيانة  
        $table->boolean('has_automated_systems')->default(false); // هل يحتوي على أنظمة آلية؟  
        $table->float('temperature')->nullable(); // درجة الحرارة داخل المستودع  
        $table->float('humidity')->nullable(); // نسبة الرطوبة داخل المستودع  
        $table->timestamps(); // تاريخ الإنشاء والتحديث  
        
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
