<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductsTable extends Migration
{
    /**
     * تشغيل الترحيل.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id()->comment('المعرّف الفريد للمنتج داخل المستودع');
            
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade')
                ->comment('المعرّف الخاص بالمنتج من جدول المنتجات');

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete()
                ->comment('المعرّف الخاص بالفرع الذي يوجد به المنتج');

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->onDelete('cascade')
                ->comment('المستودع الذي يتم تخزين المنتج فيه');

            $table->foreignId('storage_area_id')
                ->constrained('warehouse_storage_areas')
                ->onDelete('cascade')
                ->comment('الموقع التخزيني داخل المستودع مثل المنطقة أو القسم');

            $table->string('shelf_location')
                ->nullable()
                ->comment('رقم الرف أو الموقع الدقيق لتخزين المنتج داخل المستودع');

            $table->unsignedTinyInteger('inventory_movement_type')
                ->comment('نوع الحركة المخزنية: 1 للإدخال أو الإخراج، 2 للتحويل المخزني');

            $table->unsignedBigInteger('created_user')
                ->nullable()
                ->comment('رقم المستخدم الذي قام بإضافة هذا السجل');

            $table->unsignedBigInteger('updated_user')
                ->nullable()
                ->comment('رقم المستخدم الذي قام بآخر تحديث لهذا السجل');

            $table->timestamps();
        });
    }

    /**
     * التراجع عن الترحيل.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_products');
    }
}
