@php
use App\Models\User;

$user = Auth::user();
$role = $user ? $user->roles()->first() : null;
 
$NavbarLinks = [
  [
    'text' => 'إدارة المستودعات',
    'children' => [
        ['href' => '/warehouses/create', 'icon' => 'plus', 'text' => 'إضافة مستودع جديد'],
        ['href' => '/warehouses', 'icon' => 'list', 'text' => 'قائمة المستودعات'],
        ['href' => '/warehouses/locations', 'icon' => 'map-marker', 'text' => 'إدارة مواقع المستودع'],
        ['href' => '/warehouses/zone-management', 'icon' => 'square', 'text' => 'إدارة المناطق التخزينية'],
        ['href' => '/warehouses/security', 'icon' => 'shield-alt', 'text' => 'إدارة الأمان والتصاريح'],
        ['href' => '/warehouses/audit', 'icon' => 'check-circle', 'text' => 'التدقيق والمراجعة'],
        ['href' => '/warehouses/reports', 'icon' => 'file-alt', 'text' => 'تقارير المستودع'],
        ['href' => '/warehouses/settings', 'icon' => 'cogs', 'text' => 'إعدادات النظام'],
    ],
],
    [
        'text' => 'إدارة المخزون',
        'children' => [
            ['href' => '/inventory/add', 'icon' => 'plus', 'text' => 'إضافة عناصر جديدة'],
            ['href' => '/inventory/update-quantities', 'icon' => 'edit', 'text' => 'تحديث الكميات'],
            ['href' => '/inventory/distribute', 'icon' => 'arrows-alt', 'text' => 'توزيع المخزون'],
            ['href' => '/inventory/monitor', 'icon' => 'eye', 'text' => 'مراقبة حالة المخزون'],
        ],
    ],
    [
        'text' => 'إدارة الشحنات',
        'children' => [
            ['href' => '/shipments/receive', 'icon' => 'inbox', 'text' => 'استلام الشحنات'],
            ['href' => '/shipments/send', 'icon' => 'paper-plane', 'text' => 'إرسال الشحنات'],
            ['href' => '/shipments/track', 'icon' => 'map-marker-alt', 'text' => 'متابعة حالة الشحنات'],
        ],
    ],
    [
        'text' => 'إدارة الفواتير',
        'children' => [
            ['href' => '/invoices/sales', 'icon' => 'receipt', 'text' => 'إنشاء فواتير المبيعات'],
            ['href' => '/invoices/purchases', 'icon' => 'shopping-cart', 'text' => 'إنشاء فواتير المشتريات'],
            ['href' => '/invoices/track', 'icon' => 'search', 'text' => 'تتبع تفاصيل الفواتير'],
        ],
    ],
    [
        'text' => 'إدارة طلبات الموردين',
        'children' => [
            ['href' => '/supplier-orders/create', 'icon' => 'plus-square', 'text' => 'إنشاء طلبات الشراء'],
            ['href' => '/supplier-orders/track', 'icon' => 'tasks', 'text' => 'متابعة حالة الطلبات'],
            ['href' => '/supplier-orders/validate', 'icon' => 'check-circle', 'text' => 'التأكد من الكميات المطلوبة'],
        ],
    ],
    [
        'text' => 'إدارة العوائد (المرتجعات)',
        'children' => [
            ['href' => '/returns/process', 'icon' => 'cogs', 'text' => 'معالجة المرتجعات'],
            ['href' => '/returns/supplier', 'icon' => 'truck-loading', 'text' => 'إرسال المرتجعات للموردين'],
        ],
    ],
    [
        'text' => 'إدارة الموردين',
        'children' => [
            ['href' => '/suppliers/add', 'icon' => 'user-plus', 'text' => 'إضافة وتحديث بيانات الموردين'],
            ['href' => '/suppliers/track', 'icon' => 'clipboard-check', 'text' => 'متابعة حالة التعامل مع الموردين'],
            ['href' => '/suppliers/details', 'icon' => 'info-circle', 'text' => 'إدارة تفاصيل الموردين'],
        ],
    ],
    [
        'text' => 'إدارة المستخدمين',
        'children' => [
            ['href' => '/users', 'icon' => 'user-edit', 'text' => 'إدارة بيانات المستخدمين'],
            ['href' => '/users/permissions', 'icon' => 'key', 'text' => 'منح الأذونات والصلاحيات'],
            ['href' => '/users/activity', 'icon' => 'chart-pie', 'text' => 'مراقبة نشاط المستخدمين'],
        ],
    ],
    [
        'text' => 'تتبع الكميات',
        'children' => [
            ['href' => '/tracking/warehouses', 'icon' => 'warehouse', 'text' => 'تتبع الكميات في المستودعات والمحلات'],
            ['href' => '/tracking/update', 'icon' => 'sync', 'text' => 'تحديث الكميات بناءً على الشحنات والمبيعات'],
        ],
    ],
    [
        'text' => 'إنشاء التقارير',
        'children' => [
            ['href' => '/reports/inventory', 'icon' => 'clipboard-list', 'text' => 'تقارير المخزون'],
            ['href' => '/reports/shipments', 'icon' => 'shipping-fast', 'text' => 'تقارير الشحنات'],
            ['href' => '/reports/invoices', 'icon' => 'file-alt', 'text' => 'تقارير الفواتير'],
        ],
    ],
    [
        'text' => 'إدارة العملاء والمحلات',
        'children' => [
            ['href' => '/customers-stores/add', 'icon' => 'address-card', 'text' => 'إضافة وتحديث بيانات العملاء والمحلات'],
            ['href' => '/customers-stores/orders', 'icon' => 'shopping-cart', 'text' => 'إدارة الطلبات من العملاء'],
            ['href' => '/customers-stores/inventory', 'icon' => 'boxes', 'text' => 'إدارة المخزون في المحلات'],
        ],
    ],
    [
        'text' => 'إدارة مندوبين المحلات',
        'children' => [
            ['href' => '/representatives/add', 'icon' => 'user-plus', 'text' => 'إضافة وتحديث بيانات المندوبين'],
            ['href' => '/representatives/tasks', 'icon' => 'tasks', 'text' => 'تتبع المهام'],
            ['href' => '/representatives/communication', 'icon' => 'comments', 'text' => 'إدارة التواصل بين المحلات والمستودعات'],
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

