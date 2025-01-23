<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الدور
            $table->text('description')->nullable(); // وصف الدور
            $table->timestamps(); // لتخزين وقت الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
