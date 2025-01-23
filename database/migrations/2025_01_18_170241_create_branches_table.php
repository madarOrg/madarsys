<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Branch name
            $table->string('address')->nullable(); // Branch address
            $table->text('contact_info')->nullable(); // Contact information
            $table->foreignId('company_id') // Foreign key to companies table
                  ->constrained('companies')
                  ->onDelete('cascade'); // Delete branches if the company is deleted
            $table->timestamps(); // Created at and Updated at timestamps
            
            // Create a composite index on (id, company_id) if you plan to use it for foreign key relations
            $table->unique(['id', 'company_id']); // Ensures uniqueness for combination of id and company_id
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
