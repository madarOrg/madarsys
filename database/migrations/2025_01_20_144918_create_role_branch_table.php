<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('role_branch', function (Blueprint $table) {
        $table->foreignId('role_id')->constrained()->onDelete('cascade');
        $table->foreignId('branch_id')->constrained()->onDelete('cascade');
        $table->foreignId('company_id')->constrained()->onDelete('cascade');
        $table->timestamps();
         // قيد للتأكد أن branch_id مرتبط بـ company_id
         $table->foreign(['branch_id', 'company_id'])
         ->references(['id', 'company_id'])
         ->on('branches')
         ->onDelete('cascade');
    });
    
}

public function down()
{
    Schema::dropIfExists('user_branch');
}

};
