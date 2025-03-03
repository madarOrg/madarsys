<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('tax', 5, 2)->nullable(); // الضريبة (%)
            $table->decimal('discount', 5, 2)->nullable(); // التخفيضات (%)
            $table->string('supplier_contact')->nullable(); // رقم المورد
            $table->date('purchase_date')->nullable(); // تاريخ الشراء
            $table->date('manufacturing_date')->nullable(); // تاريخ التصنيع
            $table->date('expiration_date')->nullable(); // تاريخ انتهاء المنتج
            $table->date('last_updated')->nullable(); // تاريخ آخر تحديث
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('tax');
            $table->dropColumn('discount');
            $table->dropColumn('supplier_contact');
            $table->dropColumn('purchase_date');
            $table->dropColumn('manufacturing_date');
            $table->dropColumn('expiration_date');
            $table->dropColumn('last_updated');
        });
    }
};
