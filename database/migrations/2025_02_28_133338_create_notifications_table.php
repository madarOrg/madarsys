<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // إنشاء جدول قوالب الإشعارات
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null')->comment('معرف الفرع المرتبط بالقالب');

            $table->string('type')->unique()->comment('نوع الإشعار');
            $table->string('message_template')->comment('نموذج نص الإشعار مع المتغيرات');
            $table->timestamps();
        });
        // إدخال البيانات بعد إنشاء الجداول
        // DB::table('notification_templates')->insert([
        //     ['branch_id' => 1, 'type' => 'new_order', 'message_template' => 'تم استلام طلب جديد للمنتج: {{product_name}}. الكمية المطلوبة: {{quantity}}. يرجى المراجعة وإتمام الإجراءات اللازمة.'],
        //     ['branch_id' => 1, 'type' => 'restocking', 'message_template' => 'تمت إضافة كمية جديدة من المنتج: {{product_name}}. الكمية المضافة: {{quantity}}. تحقق من المخزون الحالي.'],
        //     ['branch_id' => 1, 'type' => 'low_stock', 'message_template' => 'تحذير: مخزون المنتج {{product_name}} منخفض. الكمية المتوفرة حالياً: {{quantity}}. يرجى إعادة التوريد في أقرب وقت.'],
        //     ['branch_id' => 1, 'type' => 'product_dispatch', 'message_template' => 'تم إخراج المنتج: {{product_name}} من المستودع. الكمية: {{quantity}}. تم شحنها إلى {{destination}}.'],
        //     ['branch_id' => 1, 'type' => 'inventory_check', 'message_template' => 'تم إجراء فحص المخزون للمنتج: {{product_name}}. الكمية المتوافقة مع المخزون: {{quantity}}. يرجى التحقق من التفاوت إذا لزم الأمر.'],
        //     ['branch_id' => 1, 'type' => 'order_status_update', 'message_template' => 'تم تحديث حالة طلب المنتج {{product_name}}. الحالة الجديدة: {{order_status}}. يرجى متابعة التفاصيل.'],
        //     ['branch_id' => 1, 'type' => 'shipping_delay', 'message_template' => 'تنبيه: تم تأخير شحن المنتج {{product_name}} بسبب {{delay_reason}}. يرجى مراجعة الحالة.'],
        //     ['branch_id' => 1, 'type' => 'product_expiry', 'message_template' => 'تحذير: المنتج {{product_name}} سينتهي صلاحيته قريباً في {{expiry_date}}. يرجى اتخاذ الإجراءات اللازمة.'],
        //     ['branch_id' => 1, 'type' => 'stock_upgrade', 'message_template' => 'تمت ترقية مستوى المخزون للمنتج {{product_name}}. الكمية الجديدة المتوفرة: {{quantity}}.'],
        //     ['branch_id' => 1, 'type' => 'quantity_decrease', 'message_template' => 'تحذير: تم تقليص كمية المنتج {{product_name}} أثناء النقل. الكمية المعدلة: {{quantity}}.'],
        //     ['branch_id' => 1, 'type' => 'inventory_classification', 'message_template' => 'تم تصنيف المنتج {{product_name}} في الفئة {{category}}. يرجى تحديث السجلات وفقًا لذلك.'],
        //     ['branch_id' => 1, 'type' => 'stock_status_update', 'message_template' => 'تم تحديث حالة المخزون للمنتج {{product_name}}. الحالة الجديدة: {{stock_status}}.'],
        //     ['branch_id' => 1, 'type' => 'product_storage', 'message_template' => 'تم تخزين المنتج {{product_name}} في المستودع {{warehouse_name}}. الكمية المخزنة: {{quantity}}.'],
        //     ['branch_id' => 1, 'type' => 'supplier_delivery', 'message_template' => 'تم استلام شحنة من المورد للمنتج {{product_name}}. الكمية المستلمة: {{quantity}}. يرجى التحقق من جودة الشحنة.'],
        // ]);
        // إنشاء جدول الإشعارات
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null')->comment('معرف الفرع المرتبط بالقالب');

            $table->foreignId('template_id')->nullable()->constrained('notification_templates')->onDelete('set null')->comment('معرف القالب المستخدم');
            $table->foreignId('product_id')->nullable()->index()->comment('معرف المنتج');
            $table->foreignId('inventory_request_id')->nullable()->index()->comment('معرف الطلب المتعلق بالإشعار');
            $table->integer('quantity')->nullable()->comment('الكمية المتأثرة بالإشعار');
            $table->integer('status')->default('0')->comment('حالة التنبيه');
            $table->integer('priority')->default('2')->comment('أولوية التنبيه');
            $table->timestamp('due_date')->nullable()->comment('التاريخ النهائي للإشعار');
            $table->foreignId('department_id')->nullable()->index()->comment('معرف القسم');
            $table->foreignId('warehouse_id')->nullable()->index()->comment('معرف المستودع');
            $table->foreignId('created_user')->nullable()->constrained('users')->onDelete('set null')->comment('المستخدم الذي أنشأ الإشعار');
            $table->foreignId('updated_user')->nullable()->constrained('users')->onDelete('set null')->comment('المستخدم الذي قام بآخر تحديث');
            $table->timestamps();
        });

        // إنشاء جدول إشعارات المستخدم
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null')->comment('معرف الفرع المرتبط بالقالب');

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('معرف المستخدم الذي يستلم الإشعار');
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade')->comment('معرف الإشعار الرئيسي');
            $table->string('message')->comment('نص الإشعار بعد استبدال القيم');
            $table->boolean('is_read')->default(false)->comment('هل تم قراءة الإشعار');
            $table->foreignId('created_user')->nullable()->constrained('users')->onDelete('set null')->comment('المستخدم الذي أنشأ الإشعار');
            $table->foreignId('updated_user')->nullable()->constrained('users')->onDelete('set null')->comment('المستخدم الذي قام بآخر تحديث');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_templates');
    }
};
