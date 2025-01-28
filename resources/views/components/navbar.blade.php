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
<header>
  <div class="m-4 flex justify-between items-end lg:order-2">
    <!-- الجزء الخاص بالشعار في أقصى اليمين مع تأثير الدوران -->
    <div class="flex justify-end items-center">
      <a href="/" class="flex items-center">
        <div class="mr-3 h-6 sm:h-9 rotate" alt="مدار">
          <x-logo href="/" showText="true" />
        </div> 
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">مدار</span>
      </a>
    <li class="flex items-center px-4">
      <a href="{{ route('companies.index') }}" class="p-0 transition-all text-sm ease-nav-brand text-slate-500 dark:text-white">
        <i class="cursor-pointer fa fa-cog" aria-hidden="true"></i>
      </a>
    </li>
  
  </div>
  <!-- الجزء الخاص بالروابط والإعدادات في أقصى اليسار -->
      <div class="flex justify-start items-center  lg:order-2">
        <ul class="flex flex-row justify-start pl-0 mb-0 list-none md-max:w-full">
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
    
          
          <div class="flex items-center  pr-6">
            <button id="theme-toggle" type="button" class="text-gray-900 dark:text-white">
              <i class="fas fa-moon"></i>
            </button>
          </div>
    
          <!-- زر القائمة المنسدلة (الهامبورجر) للموبايل -->
          <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
            <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
          </button>
        </ul>
      </div>
    </div>
    
    <div class="max-w-screen-xl flex flex-wrap justify-between items-center mx-auto">
      <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
        <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-4 lg:mt-0">
          @foreach ($NavbarLinks as $menu)
            <li class="relative group">
              <a href="#" class="block py-2 pr-4 pl-3 text-xs text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700 transition-transform transform hover:scale-110">
                {{ $menu['text'] }}
              </a>
              @if (isset($menu['children']) && count($menu['children']) > 0)
                <ul class="absolute z-50 left-0 hidden w-48 bg-white shadow-lg dark:bg-gray-800 group-hover:block">
                  @foreach ($menu['children'] as $child)
                    <li>
                      <a href="{{ $child['href'] }}" class="block px-4 py-2 text-xs text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">
                        <i class="{{ $child['icon'] }}"></i> {{ $child['text'] }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </li>
          @endforeach
        </ul>
        
      </div>
    </div>
  </nav>
</header>

<script>
// Optionally, you can use the following script for mobile dropdown
document.querySelectorAll('li.relative > a').forEach(item => {
  item.addEventListener('click', function(event) {
    const submenu = this.nextElementSibling; // Find the submenu (if any)
    if (submenu && submenu.classList.contains('hidden')) {
      submenu.classList.remove('hidden'); // Show submenu
    } else if (submenu) {
      submenu.classList.add('hidden'); // Hide submenu
    }
  });
});
</script>