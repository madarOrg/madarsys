<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, Permission, RolePermission};

class RolePermissionsSeeder extends Seeder
{
    public function run()
    {
        // افترض أن لديك بعض الأدوار والصلاحيات في قاعدة البيانات
        $roles = Role::all();
        $permissions = Permission::all();

        // تحقق إذا كانت هناك أدوار وصلاحيات
        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                // أضف الصلاحية إلى الدور
                RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                    'status' => 1, // تعيينه إلى فعال
                    'status_updated_at' => now(),
                ]);
            }
        }
    }
}
