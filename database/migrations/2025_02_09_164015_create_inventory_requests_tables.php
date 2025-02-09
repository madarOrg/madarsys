<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Create request_types table
        if (!Schema::hasTable('request_types')) {
            Schema::create('request_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique()->comment('اسم نوع الطلب');
                $table->text('description')->nullable()->comment('وصف لنوع الطلب');
                $table->timestamps();
            });
    
            // Insert base values
            DB::table('request_types')->insert([
                ['name' => 'طلب توريد', 'description' => 'طلب توريد من الموردين'],
                ['name' => 'طلب صرف', 'description' => 'طلب صرف داخلي للأقسام'],
                ['name' => 'طلب ارجاع', 'description' => 'طلب إرجاع المنتجات'],
                ['name' => 'طلب اصلاح', 'description' => 'طلب إصلاح المنتجات'],
                ['name' => 'طلب اعارة', 'description' => 'طلب إعارة المنتجات'],
                ['name' => 'طلب تحويل', 'description' => 'طلب تحويل المخزون بين المستودعات'],
            ]);
        }
    
        // Create inventory_requests table
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_type_id')->constrained('transaction_types')->onDelete('restrict')
                ->comment('نوع العملية المخزنية المرتبطة بالطلب');
            $table->timestamp('request_date')->useCurrent()->comment('تاريخ الطلب');
            $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null')->comment('الشريك (مورد/عميل)');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null')->comment('القسم الداخلي');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade')->comment('المستودع المعني');
            $table->enum('status', ['قيد الانتظار', 'موافق عليه', 'مرفوض', 'مكتمل'])
                ->nullable()->comment('حالة الطلب: 1 قيد الانتظار، 2 موافق عليه، 3 مرفوض، 4 مكتمل');
            $table->text('notes')->nullable()->comment('ملاحظات إضافية');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->comment('الفرع التابع له الطلب');
            $table->timestamps();
        });
    
        // Create inventory_request_details table
        Schema::create('inventory_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_request_id')->constrained('inventory_requests')->onDelete('cascade')
                ->comment('معرف الطلب الرئيسي');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')
                ->comment('المنتج المرتبط بالطلب');
            $table->integer('quantity')->comment('الكمية المطلوبة');
            $table->decimal('unit_price', 10, 2)->nullable()->comment('سعر الوحدة للمنتج');
            $table->decimal('total', 15, 2)->nullable()->comment('إجمالي السعر');
            $table->foreignId('warehouse_location_id')->nullable()->constrained('warehouse_locations')->onDelete('set null')
                ->comment('موقع التخزين داخل المستودع');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->comment('الفرع التابع له الطلب');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('inventory_request_items');
        Schema::dropIfExists('inventory_requests');
        Schema::dropIfExists('request_types');
    }
};
