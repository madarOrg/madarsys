<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Module, ModuleAction};

class ModuleActionsSeeder extends Seeder {
    public function run() {
        $modules = Module::all();
        $navbarLinks = [
            [
                'text' => 'إدارة المستخدمين',
                'children' => [
                    ['href' => '/users', 'icon' => 'fa fa-user-edit text-red-500', 'text' => 'إدارة بيانات المستخدمين'],
                    ['href' => '/roles', 'icon' => 'fa fa-user-edit text-red-500', 'text' => 'إدارة أدوار المستخدمين'],
                    ['href' => '/users/permissions', 'icon' => 'fa fa-key text-orange-500', 'text' => 'منح الأذونات والصلاحيات'],
                    ['href' => '/users/activity', 'icon' => 'fa fa-chart-pie text-green-500', 'text' => 'مراقبة نشاط المستخدمين'],
                ],
            ],
            [
                'text' => 'إدارة المستودعات',
                'children' => [
                    ['href' => '/warehouses/create', 'icon' => 'fa fa-plus text-green-500', 'text' => 'إضافة مستودع جديد'],
                    ['href' => '/warehouses', 'icon' => 'fa fa-list text-blue-500', 'text' => 'قائمة المستودعات'],
                    ['href' => '/warehouses/locations', 'icon' => 'fa fa-map-marker text-red-500', 'text' => 'إدارة مواقع المستودع'],
                    ['href' => '/warehouses/zone-management', 'icon' => 'fa fa-square text-purple-500', 'text' => 'إدارة المناطق التخزينية'],
                    ['href' => '/warehouses/security', 'icon' => 'fa fa-shield-alt text-orange-500', 'text' => 'إدارة الأمان والتصاريح'],
                    ['href' => '/warehouses/audit', 'icon' => 'fa fa-check-circle text-teal-500', 'text' => 'التدقيق والمراجعة'],
                    ['href' => '/warehouses/reports', 'icon' => 'fa fa-file-alt text-gray-500', 'text' => 'تقارير المستودع'],
                    ['href' => '/warehouses/settings', 'icon' => 'fa fa-cogs text-yellow-500', 'text' => 'إعدادات النظام'],
                ],
            ],
            [
                'text' => 'إدارة المخزون',
                'children' => [
                    ['href' => '/inventory/add', 'icon' => 'fa fa-plus text-green-500', 'text' => 'إضافة عناصر جديدة'],
                    ['href' => '/inventory/update-quantities', 'icon' => 'fa fa-edit text-blue-500', 'text' => 'تحديث الكميات'],
                    ['href' => '/inventory/distribute', 'icon' => 'fa fa-arrows-alt text-purple-500', 'text' => 'توزيع المخزون'],
                    ['href' => '/inventory/monitor', 'icon' => 'fa fa-eye text-teal-500', 'text' => 'مراقبة حالة المخزون'],
                ],
            ],
            [
                'text' => 'إدارة الشحنات',
                'children' => [
                    ['href' => '/shipments/receive', 'icon' => 'fa fa-inbox text-green-500', 'text' => 'استلام الشحنات'],
                    ['href' => '/shipments/send', 'icon' => 'fa fa-paper-plane text-blue-500', 'text' => 'إرسال الشحنات'],
                    ['href' => '/shipments/track', 'icon' => 'fa fa-map-marker-alt text-red-500', 'text' => 'متابعة حالة الشحنات'],
                ],
            ],
            [
                'text' => 'إدارة الفواتير',
                'children' => [
                    ['href' => '/invoices/sales', 'icon' => 'fa fa-receipt text-yellow-500', 'text' => 'إنشاء فواتير المبيعات'],
                    ['href' => '/invoices/purchases', 'icon' => 'fa fa-shopping-cart text-green-500', 'text' => 'إنشاء فواتير المشتريات'],
                    ['href' => '/invoices/track', 'icon' => 'fa fa-search text-teal-500', 'text' => 'تتبع تفاصيل الفواتير'],
                ],
            ],
            [
                'text' => 'إدارة طلبات الموردين',
                'children' => [
                    ['href' => '/supplier-orders/create', 'icon' => 'fa fa-plus-square text-green-500', 'text' => 'إنشاء طلبات الشراء'],
                    ['href' => '/supplier-orders/track', 'icon' => 'fa fa-tasks text-blue-500', 'text' => 'متابعة حالة الطلبات'],
                    ['href' => '/supplier-orders/validate', 'icon' => 'fa fa-check-circle text-red-500', 'text' => 'التأكد من الكميات المطلوبة'],
                ],
            ],
            [
                'text' => 'إدارة العوائد (المرتجعات)',
                'children' => [
                    ['href' => '/returns/process', 'icon' => 'fa fa-cogs text-yellow-500', 'text' => 'معالجة المرتجعات'],
                    ['href' => '/returns/supplier', 'icon' => 'fa fa-truck-loading text-purple-500', 'text' => 'إرسال المرتجعات للموردين'],
                ],
            ],
            [
                'text' => 'إدارة الموردين',
                'children' => [
                    ['href' => '/suppliers/add', 'icon' => 'fa fa-user-plus text-green-500', 'text' => 'إضافة وتحديث بيانات الموردين'],
                    ['href' => '/suppliers/track', 'icon' => 'fa fa-clipboard-check text-teal-500', 'text' => 'متابعة حالة التعامل مع الموردين'],
                    ['href' => '/suppliers/details', 'icon' => 'fa fa-info-circle text-blue-500', 'text' => 'إدارة تفاصيل الموردين'],
                ],
            ],
            [
                'text' => 'تتبع الكميات',
                'children' => [
                    ['href' => '/tracking/warehouses', 'icon' => 'fa fa-warehouse text-purple-500', 'text' => 'تتبع الكميات في المستودعات والمحلات'],
                    ['href' => '/tracking/update', 'icon' => 'fa fa-sync text-blue-500', 'text' => 'تحديث الكميات بناءً على الشحنات والمبيعات'],
                ],
            ],
            [
                'text' => 'إنشاء التقارير',
                'children' => [
                    ['href' => '/reports/inventory', 'icon' => 'fa fa-clipboard-list text-green-500', 'text' => 'تقارير المخزون'],
                    ['href' => '/reports/shipments', 'icon' => 'fa fa-shipping-fast text-teal-500', 'text' => 'تقارير الشحنات'],
                    ['href' => '/reports/invoices', 'icon' => 'fa fa-file-alt text-orange-500', 'text' => 'تقارير الفواتير'],
                ],
            ],
            [
                'text' => 'إدارة العملاء والمحلات',
                'children' => [
                    ['href' => '/customers-stores/add', 'icon' => 'fa fa-address-card text-blue-500', 'text' => 'إضافة وتحديث بيانات العملاء والمحلات'],
                    ['href' => '/customers-stores/orders', 'icon' => 'fa fa-shopping-cart text-green-500', 'text' => 'إدارة الطلبات من العملاء'],
                    ['href' => '/customers-stores/inventory', 'icon' => 'fa fa-boxes text-yellow-500', 'text' => 'إدارة المخزون في المحلات'],
                ],
            ],
            [
                'text' => 'إدارة مندوبين المحلات',
                'children' => [
                    ['href' => '/representatives/add', 'icon' => 'fa fa-user-plus text-green-500', 'text' => 'إضافة وتحديث بيانات المندوبين'],
                    ['href' => '/representatives/tasks', 'icon' => 'fa fa-tasks text-blue-500', 'text' => 'تتبع المهام'],
                    ['href' => '/representatives/communication', 'icon' => 'fa fa-comments text-purple-500', 'text' => 'إدارة التواصل بين المحلات والمستودعات'],
                ],
            ],
        ];

        foreach ($modules as $module) {
            $section = collect($navbarLinks)->firstWhere('text', $module->name);

            if ($section && isset($section['children'])) {  // تحقق من وجود الأطفال
                foreach ($section['children'] as $child) {
                    ModuleAction::create([
                        'module_id'   => $module->id,
                        'branch_id'   => 2, // تعيين الفرع إلى 2
                        'name'        => $child['text'],
                        'action_key'  => $this->extractActionKey($child['href']),
                        'route'       => $child['href'],
                        'icon'        => $child['icon'] ?? null,
                    ]);
                }
            }
        }
    }

    public function extractActionKey($href) {  // جعلها public
        $parts = explode('/', trim($href, '/'));
        return end($parts) ?: 'view';
    }
}
