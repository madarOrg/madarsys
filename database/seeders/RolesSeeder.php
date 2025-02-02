<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, Permission};

class RolesSeeder extends Seeder {
    public function run() {
        // 1. إنشاء الأدوار الأساسية
        $roles = [
            [
                'name' => 'مدير النظام',
                'permissions' => 'all'
            ],
            [
                'name' => 'مدير شركة',
                'permissions' => ['warehouses.view', 'warehouses.create', 'warehouses.edit']
            ],
            [
                'name' => 'مشرف فرع',
                'permissions' => ['branches.view', 'branches.edit']
            ],
            [
                'name' => 'مسؤول مستودع',
                'permissions' => ['warehouses.view', 'warehouses.manage']
            ],
            [
                'name' => 'موظف شحن',
                'permissions' => ['shipments.view', 'shipments.process']
            ],
            [
                'name' => 'مراقب مخزون',
                'permissions' => ['inventory.view', 'inventory.report']
            ]
        ];

        foreach ($roles as $roleData) {
            // التحقق من إنشاء الدور
            $role = Role::firstOrCreate(['name' => $roleData['name']]);

            // 2. جلب الصلاحيات المناسبة لكل دور
            if ($roleData['permissions'] === 'all') {
                $permissions = Permission::pluck('id');
            } else {
                $permissions = Permission::whereIn('permission_key', $roleData['permissions'])->pluck('id');
            }

            // 3. تحديث الصلاحيات المرتبطة بالدور
            $role->permissions()->sync($permissions);

            // 4. إضافة صلاحيات افتراضية مثل لوحة التحكم والملف الشخصي
            $this->addDefaultPermissions($role);
        }
    }

    private function addDefaultPermissions($role) {
        // جلب الصلاحيات المشتركة
        $defaultPermissions = Permission::whereIn('permission_key', ['dashboard.view', 'profile.view'])->pluck('id');

        // ربط الصلاحيات الافتراضية بالدور
        $role->permissions()->syncWithoutDetaching($defaultPermissions);
    }
}
