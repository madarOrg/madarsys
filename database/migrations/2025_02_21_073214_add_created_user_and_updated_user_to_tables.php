<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'branches','categories', 'companies', 'departments',
             'inventory', 'inventory_request_details', 'inventory_requests',
            'inventory_transaction_items', 'inventory_transactions',  
            'module_actions', 'modules', 'partner_types', 'partners',
             'permissions', 'products', 'request_types',
            'role_branch', 'role_company', 'role_permissions', 'role_user', 'role_warehouse',
            'roles',  'settings', 'transaction_types', 'units', 'users',
            'warehouse_category_warehouse', 'warehouse_locations', 'warehouse_reports',
            'warehouse_storage_areas', 'warehouses', 'zones'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('created_user')->nullable()->after('created_at');
                $table->unsignedBigInteger('updated_user')->nullable()->after('updated_at');
                            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'branches','categories', 'companies', 'departments',
            'inventory', 'inventory_request_details', 'inventory_requests',
           'inventory_transaction_items', 'inventory_transactions',  
           'module_actions', 'modules', 'partner_types', 'partners',
            'permissions', 'products', 'request_types',
           'role_branch', 'role_company', 'role_permissions', 'role_user', 'role_warehouse',
           'roles',  'settings', 'transaction_types', 'units', 'users',
           'warehouse_category_warehouse', 'warehouse_locations', 'warehouse_reports',
           'warehouse_storage_areas', 'warehouses', 'zones'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
            
                $table->dropColumn('created_user');
                $table->dropColumn('updated_user');
            });
               // تعيين أول مستخدم بشكل افتراضي بعد الإضافة
               DB::table($table)->update([
                'created_user' => DB::table('users')->min('id') ?? 1,
                'updated_user' => DB::table('users')->min('id') ?? 1
            ]);
        }
    }
};
