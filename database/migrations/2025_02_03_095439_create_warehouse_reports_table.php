<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('report_type'); // inventory, movement, etc.
            $table->json('report_data');
            $table->dateTime('report_date');
            $table->string('generated_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_reports');
    }
};
