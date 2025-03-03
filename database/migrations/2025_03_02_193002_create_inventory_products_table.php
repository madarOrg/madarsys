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
    
            // استبدال shelf_location بـ location_id
            $table->foreignId('location_id')
                ->nullable()
                ->constrained('warehouse_locations')
                ->nullOnDelete()
                ->comment('الموقع التخزيني الدقيق للمنتج داخل المستودع');
    
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
