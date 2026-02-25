<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionTypesToRolePermissionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->boolean('can_view')->default(true)->after('permission_id')->comment('السماح بالعرض');
            $table->boolean('can_create')->default(true)->after('can_view')->comment('السماح بالإضافة');
            $table->boolean('can_update')->default(true)->after('can_create')->comment('السماح بالتعديل');
            $table->boolean('can_delete')->default(true)->after('can_update')->comment('السماح بالحذف');
        });
    }

    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropColumn(['can_view', 'can_create', 'can_update', 'can_delete']);
        });
    }
};
