<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->tinyInteger('effect')->nullable()->after('transaction_type_id')
                ->comment('تحديد تأثير العملية على المخزون: + زيادة، - نقصان، 0 محايدة');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('effect');
        });
    }
};
return new class extends Migration
{
    public function up()
    {
        // إضافة جدول الحركات المخزنية (inventory_transactions)
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // ربط الحركة بالطلب
            $table->foreignId('product_id')->constrained('products'); // ربط المنتج بالحركة
            $table->decimal('quantity', 10, 2); // الكمية المتأثرة
            $table->decimal('price', 15, 2); // سعر المنتج عند الحركه
            $table->enum('transaction_type', ['in', 'out'])->comment('in = إضافة إلى المخزون, out = خصم من المخزون');
            $table->timestamps();
        });
    }

    public function down()
    {
        // حذف جدول الحركات المخزنية
        Schema::dropIfExists('inventory_transactions');
    }
};

