<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToInventoryTransactionItemsTable extends Migration
{
    public function up()
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('1 = قيد التنفيذ، 2 = مكتملة، 3 = مُلغاة');

            $table->unsignedTinyInteger('result')
                ->default(1)
                ->comment('نتيجة الجرد: 1 = مطابقة، 2 = تلف، 3 = فقدان، 4 = نقل');

            $table->integer('expected_audit_quantity')
                ->nullable()
                ->comment('الكمية المتوقعة من الجرد');
        });
    }

    public function down()
    {
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            $table->dropColumn(['status', 'result', 'expected_audit_quantity']);
        });
    }
}
