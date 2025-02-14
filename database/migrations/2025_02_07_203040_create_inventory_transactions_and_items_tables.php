<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('inventory_transactions', function (Blueprint $table) {
        $table->id(); // رقم العملية
        $table->foreignId('transaction_type_id')->constrained('transaction_types')->onDelete('cascade'); // نوع العملية
        $table->date('transaction_date'); // تاريخ العملية
        $table->string('reference')->nullable(); // الرقم المرجعي
        $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null'); // الشريك (مورد/عميل)
        $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null'); // القسم الداخلي
        $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // المستودع المعني
        $table->text('notes')->nullable(); // ملاحظات إضافية
        $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
        $table->timestamps();
    });

    Schema::create('inventory_transaction_items', function (Blueprint $table) {
        $table->id(); // رقم السجل
        $table->foreignId('inventory_transaction_id')->constrained('inventory_transactions')->onDelete('cascade'); // معرف العملية
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // المنتج
        $table->integer('quantity'); // الكمية (موجبة أو سالبة)
        $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null'); // حقل الوحدة
        $table->foreignId('unit_product_id')->nullable()->constrained('units')->onDelete('set null'); // حقل الوحدة
        $table->decimal('converted_quantity', 10, 4)->nullable(); // حقل الكمية المحولة
        $table->decimal('unit_prices', 15, 2)->nullable(); // إجمالي السعر
        $table->decimal('total', 20, 6)->nullable(); // إجمالي السعر
        $table->foreignId('warehouse_location_id') // معرف موقع التخزين
            ->nullable()
            ->constrained('warehouse_locations') // ربطه بجدول warehouse_locations
            ->onDelete('set null'); // في حال الحذف، يتم تعيينه إلى null
        $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('inventory_transaction_items');
        Schema::dropIfExists('inventory_transactions');
    }
};
