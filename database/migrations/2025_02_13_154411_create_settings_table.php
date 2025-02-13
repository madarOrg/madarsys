<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });

        // إدخال الإعداد الافتراضي
        DB::table('settings')->insert([
            ['key' => 'inventory_transaction_min_date', 'value' => '2023-01-01'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
