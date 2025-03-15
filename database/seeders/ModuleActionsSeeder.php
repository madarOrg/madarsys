<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModuleActionsSeeder extends Seeder {
    
    public function run()
    {
        $menus = [
            ['id' => 42, 'module_id' => 1, 'name' => 'إدارة بيانات المستخدمين', 'action_key' => 'users.index', 'route' => '/users', 'icon' => 'fa fa-user-edit text-red-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-01-31 16:30:35'), 'branch_id' => 2],
            ['id' => 43, 'module_id' => 1, 'name' => 'إدارة أدوار المستخدمين', 'action_key' => 'roles', 'route' => '/roles', 'icon' => 'fa fa-user-edit text-red-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 44, 'module_id' => 1, 'name' => 'منح الأذونات والصلاحيات', 'action_key' => 'permissions', 'route' => '/role-permissions/index', 'icon' => 'fa fa-key text-orange-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 45, 'module_id' => 1, 'name' => 'مراقبة نشاط المستخدمين', 'action_key' => 'activity', 'route' => '/users-rolesi/ndex', 'icon' => 'fa fa-chart-pie text-green-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 46, 'module_id' => 2, 'name' => 'إضافة مستودع جديد', 'action_key' => 'create', 'route' => '/warehouses/create', 'icon' => 'fa fa-plus text-green-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 47, 'module_id' => 2, 'name' => 'قائمة المستودعات', 'action_key' => 'warehouses', 'route' => '/warehouses', 'icon' => 'fa fa-list text-blue-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 48, 'module_id' => 2, 'name' => 'إدارة مواقع المستودع', 'action_key' => 'locations', 'route' => '/warehouses', 'icon' => 'fa fa-map-marker text-red-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 49, 'module_id' => 2, 'name' => 'إدارة المناطق التخزينية', 'action_key' => 'storage-areas', 'route' => '/warehouses', 'icon' => 'fa-solid fa-store', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 50, 'module_id' => 2, 'name' => 'إدارة الأمان والتصاريح', 'action_key' => 'security', 'route' => '/warehouses/security', 'icon' => 'fa fa-shield-alt text-orange-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 51, 'module_id' => 2, 'name' => 'التدقيق والمراجعة', 'action_key' => 'audit', 'route' => '/warehouses/audit', 'icon' => 'fa fa-check-circle text-teal-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 52, 'module_id' => 2, 'name' => 'تقارير المستودع', 'action_key' => 'reports', 'route' => '/warehouse-reports', 'icon' => 'fa fa-file-alt text-gray-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 53, 'module_id' => 2, 'name' => 'قائمة الشركات', 'action_key' => 'settings', 'route' => '/companies', 'icon' => 'fa fa-list text-blue-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 54, 'module_id' => 3, 'name' => 'اضافة منتج جديد', 'action_key' => 'add', 'route' => '/products', 'icon' => 'fa fa-plus text-green-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 55, 'module_id' => 3, 'name' => 'حركات مخزنية', 'action_key' => 'update-quantities', 'route' => '/inventory/transactions/create', 'icon' => 'fa fa-edit text-blue-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 56, 'module_id' => 3, 'name' => 'توزيع المخزون', 'action_key' => 'distribute', 'route' => '/inventory/distribute', 'icon' => 'fa fa-arrows-alt text-purple-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 57, 'module_id' => 3, 'name' => 'مراقبة حالة المخزون', 'action_key' => 'monitor', 'route' => '/inventory/monitor', 'icon' => 'fa fa-eye text-teal-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 58, 'module_id' => 4, 'name' => 'استلام الشحنات', 'action_key' => 'receive', 'route' => '/shipments/receive', 'icon' => 'fa fa-inbox text-green-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 59, 'module_id' => 4, 'name' => 'إرسال الشحنات', 'action_key' => 'send', 'route' => '/shipments/send', 'icon' => 'fa fa-paper-plane text-blue-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            ['id' => 60, 'module_id' => 4, 'name' => 'متابعة حالة الشحنات', 'action_key' => 'track', 'route' => '/shipments/track', 'icon' => 'fa fa-map-marker-alt text-red-500', 'created_at' => Carbon::parse('2025-01-31 16:30:35'), 'updated_at' => Carbon::parse('2025-02-01 16:08:19'), 'branch_id' => 2],
            [
                'id' => 61,
                'module_id' => 5,
                'name' => 'فواتير المبيعات',
                'action_key' => 'sales',
                'route' => '/invoices/sale',  
                'icon' => 'fa fa-receipt text-yellow-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 62,
                'module_id' => 5,
                'name' => 'فواتير المشتريات',
                'action_key' => 'purchases',
                'route' => '/invoices/purchase',  
                'icon' => 'fa fa-shopping-cart text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            // [
            //     'id' => 63,
            //     'module_id' => 5,
            //     'name' => 'تتبع تفاصيل الفواتير',
            //     'action_key' => 'track',
            //     'route' => '/invoices/track',
            //     'icon' => 'fa fa-search text-teal-500',
            //     'created_at' => Carbon::parse('2025-01-31 16:30:35'),
            //     'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
            //     'branch_id' => 2
            // ],
            [
                'id' => 64,
                'module_id' => 6,
                'name' => 'ادارة الشركاء',
                'action_key' => 'create',
                'route' => '/partners',
                'icon' => 'fa fa-plus-square text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 65,
                'module_id' => 6,
                'name' => 'المنتجات',
                'action_key' => 'track',
                'route' => '/products',
                'icon' => 'fa fa-tasks text-blue-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 66,
                'module_id' => 3,
                'name' => 'فئات المنتجات',
                'action_key' => 'validate',
                'route' => '/categories',
                'icon' => 'fa fa-check-circle text-red-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 67,
                'module_id' => 7,
                'name' => 'معالجة المرتجعات',
                'action_key' => 'process',
                'route' => '/returns/process',
                'icon' => 'fa fa-cogs text-yellow-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 68,
                'module_id' => 7,
                'name' => 'إرسال المرتجعات للموردين',
                'action_key' => 'supplier',
                'route' => '/returns/supplier',
                'icon' => 'fa fa-truck-loading text-purple-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 69,
                'module_id' => 8,
                'name' => 'إضافة وتحديث بيانات الموردين',
                'action_key' => 'add',
                'route' => '/suppliers/add',
                'icon' => 'fa fa-user-plus text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 70,
                'module_id' => 8,
                'name' => 'متابعة حالة التعامل مع الموردين',
                'action_key' => 'track',
                'route' => '/suppliers/track',
                'icon' => 'fa fa-clipboard-check text-teal-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 71,
                'module_id' => 8,
                'name' => 'إدارة تفاصيل الموردين',
                'action_key' => 'details',
                'route' => '/suppliers/details',
                'icon' => 'fa fa-info-circle text-blue-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 72,
                'module_id' => 9,
                'name' => 'تتبع الكميات في المستودعات والمحلات',
                'action_key' => 'warehouses',
                'route' => '/tracking/warehouses',
                'icon' => 'fa fa-warehouse text-purple-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 73,
                'module_id' => 9,
                'name' => 'تحديث الكميات بناءً على الشحنات والمبيعات',
                'action_key' => 'update',
                'route' => '/tracking/update',
                'icon' => 'fa fa-sync text-blue-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 74,
                'module_id' => 10,
                'name' => 'تقارير المخزون',
                'action_key' => 'inventory',
                'route' => '/reports/inventory',
                'icon' => 'fa fa-clipboard-list text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 75,
                'module_id' => 10,
                'name' => 'تقارير الشحنات',
                'action_key' => 'shipments',
                'route' => '/reports/shipments',
                'icon' => 'fa fa-shipping-fast text-teal-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 76,
                'module_id' => 10,
                'name' => 'تقارير الفواتير',
                'action_key' => 'invoices',
                'route' => '/reports/invoices',
                'icon' => 'fa fa-file-alt text-orange-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 77,
                'module_id' => 11,
                'name' => 'إضافة وتحديث بيانات العملاء والمحلات',
                'action_key' => 'customers-stores.add',
                'route' => '/customers-stores/add',
                'icon' => 'fa fa-address-card text-blue-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 78,
                'module_id' => 11,
                'name' => 'إدارة الطلبات من العملاء',
                'action_key' => 'customers-stores.orders',
                'route' => '/customers-stores/orders',
                'icon' => 'fa fa-shopping-cart text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 79,
                'module_id' => 11,
                'name' => 'إدارة المخزون في المحلات',
                'action_key' => 'customers-stores.inventory',
                'route' => '/customers-stores/inventory',
                'icon' => 'fa fa-boxes text-yellow-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 80,
                'module_id' => 12,
                'name' => 'إضافة وتحديث بيانات المندوبين',
                'action_key' => 'representatives.add',
                'route' => '/representatives/add',
                'icon' => 'fa fa-user-plus text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 81,
                'module_id' => 12,
                'name' => 'تتبع المهام',
                'action_key' => 'representatives.tasks',
                'route' => '/representatives/tasks',
                'icon' => 'fa fa-tasks text-blue-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 82,
                'module_id' => 12,
                'name' => 'إدارة التواصل بين المحلات والمستودعات',
                'action_key' => 'representatives.communication',
                'route' => '/representatives/communication',
                'icon' => 'fa fa-comments text-purple-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 83,
                'module_id' => 1,
                'name' => 'إدارة بيانات المستخدمين',
                'action_key' => 'users.index',
                'route' => '/users',
                'icon' => 'fa fa-user-edit text-red-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 84,
                'module_id' => 2,
                'name' => 'إضافة شركة جديدة',
                'action_key' => 'companies.create',
                'route' => '/companies/create',
                'icon' => 'fa fa-plus text-green-500',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ],
            [
                'id' => 85,
                'module_id' => 2,
                'name' => 'إعدادات النظام',
                'action_key' => 'settings',
                'route' => '/settings',
                'icon' => 'fa-solid fa-gears',
                'created_at' => Carbon::parse('2025-01-31 16:30:35'),
                'updated_at' => Carbon::parse('2025-02-01 16:08:19'),
                'branch_id' => 2
            ]


        ];

        DB::table('module_actions')->insert($menus);
    }
}


