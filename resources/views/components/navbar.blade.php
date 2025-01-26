@php
use App\Models\User;

$user = Auth::user();
$role = $user ? $user->roles()->first() : null;
 
$NavbarLinks = [
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
        'text' => 'إدارة المستخدمين',
        'children' => [
            ['href' => '/users', 'icon' => 'fa fa-user-edit text-red-500', 'text' => 'إدارة بيانات المستخدمين'],
            ['href' => '/users/permissions', 'icon' => 'fa fa-key text-orange-500', 'text' => 'منح الأذونات والصلاحيات'],
            ['href' => '/users/activity', 'icon' => 'fa fa-chart-pie text-green-500', 'text' => 'مراقبة نشاط المستخدمين'],
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

@endphp

<nav class="fixed top-0 z-50 w-full border-b  border-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between w-full px-0 py-1 mx-auto flex-wrap-inherit">
      <div class="flex items-center">
        <button id="theme-toggle" type="button" class="text-gray-900 dark:text-white">
          <i class="fas fa-moon"></i>
        </button>
      </div>
      <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
        <li class="flex items-center">
          @auth
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="block px-0 py-2 font-semibold transition-all ease-nav-brand text-sm text-slate-500 dark:text-white">
                <i class="fa fa-sign-out sm:mr-1" aria-hidden="true"></i>
                <span class="hidden sm:inline">Sign Out</span>
              </button>
            </form>
          @else
            <a href="{{ route('login') }}" class="block px-0 py-2 font-semibold transition-all ease-nav-brand text-sm text-slate-500 dark:text-white">
              <i class="fa fa-user sm:mr-1" aria-hidden="true"></i>
              <span class="hidden sm:inline">Sign In</span>
            </a>
          @endauth
        </li>

        <li class="flex items-center px-4">
          <a href="{{ route('companies.index') }}" class="p-0 transition-all text-sm ease-nav-brand text-slate-500 dark:text-white">
            <i fixed-plugin-button-nav="" class="cursor-pointer fa fa-cog" aria-hidden="true"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
       <!-- Add navbar links in a separate div -->
<div id="navbar" class="flex items-center justify-center mt-0 sm:mt-0 sm:mr-4 md:mr-0 lg:flex lg:basis-auto">
  <ul class="flex space-x-6">
    @foreach($NavbarLinks as $link)
      <li class="relative">
        <a href="{{ isset($link['href']) ? $link['href'] : '#' }}" class="text-sm text-slate-500 dark:text-white hover:text-blue-500">
          {{ $link['text'] }}
        </a>
        @if(isset($link['children']) && count($link['children']) > 0)
          <ul class="absolute left-0 hidden mt-2 space-y-2 bg-white dark:bg-gray-800 rounded-lg shadow-md w-48">
            @foreach($link['children'] as $child)
              <li>
                <a href="{{ isset($child['href']) ? $child['href'] : '#' }}" class="block px-4 py-2 text-sm text-slate-500 dark:text-white hover:text-blue-500">
                  <i class="fas fa-{{ isset($child['icon']) ? $child['icon'] : 'link' }}"></i> {{ $child['text'] }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </li>
    @endforeach
  </ul>
</div>

</nav>

