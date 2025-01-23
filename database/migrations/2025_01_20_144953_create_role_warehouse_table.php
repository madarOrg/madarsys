<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_xxxxxx_create_user_warehouse_table.php
   public function up()
   {
       Schema::create('role_warehouse', function (Blueprint $table) {
           $table->foreignId('role_id') // Foreign key to roles table
                 ->constrained()
                 ->onDelete('cascade'); // Delete role_warehouse if role is deleted
           $table->foreignId('warehouse_id') // Foreign key to warehouses table
                 ->constrained()
                 ->onDelete('cascade'); // Delete role_warehouse if warehouse is deleted
           $table->foreignId('branch_id') // Foreign key to branches table
                 ->constrained()
                 ->onDelete('cascade'); // Delete role_warehouse if branch is deleted
           $table->timestamps(); // Created at and Updated at timestamps
       });
   }
   

public function down()
{
    Schema::dropIfExists('user_warehouse');
}

};
