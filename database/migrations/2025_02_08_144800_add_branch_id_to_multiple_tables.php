<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
           'categories',
            'departments',
            'modules',
            'module_actions',
            'partners',
            'partner_types',
            'permissions',
            'products',
            'users',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'categories',
            'departments',
            'modules',
            'module_actions',
            'partners',
            'partner_types',
            'permissions',
            'products',
            'users'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign([$table . '_branch_id_foreign']);
                $table->dropColumn('branch_id');
            });
        }
    }
};
