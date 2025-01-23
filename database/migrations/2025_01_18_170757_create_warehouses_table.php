<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('warehouses', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->string('name'); // Warehouse name
        $table->string('code')->unique(); // Warehouse code for identification
        $table->string('address')->nullable(); // Warehouse address
        $table->text('contact_info')->nullable(); // Contact information
        $table->foreignId('branch_id') // Foreign key to branches table
              ->constrained('branches')
              ->onDelete('cascade'); // Delete warehouses if the branch is deleted
        $table->foreignId('supervisor_id') // Foreign key to users table
              ->nullable() // Optional: If some warehouses don't have a supervisor
              ->constrained('users')
              ->onDelete('set null'); // Set to null if the supervisor is deleted
        $table->decimal('latitude', 10, 7)->nullable(); // Latitude
        $table->decimal('longitude', 10, 7)->nullable(); // Longitude
        $table->float('area', 8, 2)->nullable(); // Warehouse area in square meters
        $table->integer('shelves_count')->nullable(); // Number of shelves
        $table->float('capacity')->nullable(); // Warehouse capacity
        $table->boolean('is_smart')->default(false); // Is the warehouse smart?
        $table->boolean('has_security_system')->default(false); // Has security system
        $table->boolean('has_cctv')->default(false); // Has CCTV
        $table->boolean('is_integrated_with_wms')->default(false); // Integrated with WMS
        $table->timestamp('last_maintenance')->nullable(); // Last maintenance date
        $table->boolean('has_automated_systems')->default(false); // Has automated systems
        $table->float('temperature')->nullable(); // Temperature inside warehouse
        $table->float('humidity')->nullable(); // Humidity inside warehouse
        $table->timestamps(); // Created at and Updated at timestamps
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
