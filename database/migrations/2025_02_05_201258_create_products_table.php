<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // معرف المنتج الفريد
            $table->string('name'); // اسم المنتج
            $table->string('image')->nullable(); // إضافة عمود الصورة
            $table->text('description')->nullable(); // وصف المنتج
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // الفئه
            $table->foreignId('supplier_id')->nullable()->constrained('partners')->onDelete('set null'); // المورد (اختياري)
            $table->string('barcode')->unique()->nullable(); // الباركود
            $table->string('sku')->unique(); // كود التخزين SKU
            $table->decimal('purchase_price', 10, 2)->default(0.00); // سعر الشراء
            $table->decimal('selling_price', 10, 2)->default(0.00); // سعر البيع
            $table->unsignedInteger('stock_quantity')->default(0); // الكمية المتاحة في المخزون
            $table->unsignedInteger('min_stock_level')->default(1); // الحد الأدنى للمخزون
            $table->unsignedInteger('max_stock_level')->nullable(); // الحد الأقصى للمخزون
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // إضافة الوحدة المرتبطة
            $table->boolean('is_active')->default(true); // حالة تفعيل المنتج
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
