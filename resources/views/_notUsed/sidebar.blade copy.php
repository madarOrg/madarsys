@php
use App\Models\User;

// $user = Auth::user();
$user = User::find(1);

$role = $user->roles()->first();


$sidebarLinks = [
    [
        'href' => '/inventory',
        'icon' => 'boxes',
        'text' => 'إدارة المخزون',
        'children' => [
            ['href' => '/inventory/add', 'icon' => 'plus', 'text' => 'إضافة عناصر جديدة'],
            ['href' => '/inventory/update-quantities', 'icon' => 'edit', 'text' => 'تحديث الكميات'],
            ['href' => '/inventory/distribute', 'icon' => 'arrows-alt', 'text' => 'توزيع المخزون'],
            ['href' => '/inventory/monitor', 'icon' => 'eye', 'text' => 'مراقبة حالة المخزون'],
        ],
    ],
    [
        'href' => '/shipments',
        'icon' => 'truck',
        'text' => 'إدارة الشحنات',
        'children' => [
            ['href' => '/shipments', 'icon' => 'inbox', 'text' => 'استلام الشحنات'],
            ['href' => '/shipments/send', 'icon' => 'paper-plane', 'text' => 'إرسال الشحنات'],
            ['href' => '/shipments/track', 'icon' => 'map-marker-alt', 'text' => 'متابعة حالة الشحنات'],
        ],
    ],
    [
        'href' => '/invoices',
        'icon' => 'file-invoice-dollar',
        'text' => 'إدارة الفواتير',
        'children' => [
            ['href' => '/invoices/sales', 'icon' => 'receipt', 'text' => 'إنشاء فواتير المبيعات'],
            ['href' => '/invoices/purchases', 'icon' => 'shopping-cart', 'text' => 'إنشاء فواتير المشتريات'],
            ['href' => '/invoices/track', 'icon' => 'search', 'text' => 'تتبع تفاصيل الفواتير'],
        ],
    ],
    [
        'href' => '/supplier-orders',
        'icon' => 'shopping-bag',
        'text' => 'إدارة طلبات الموردين',
        'children' => [
            ['href' => '/supplier-orders/create', 'icon' => 'plus-square', 'text' => 'إنشاء طلبات الشراء'],
        ],
    ],
    [
        'href' => '/returns',
        'icon' => 'undo',
        'text' => 'إدارة العوائد (المرتجعات)',
        'children' => [
            ['href' => '/returns/process', 'icon' => 'cogs', 'text' => 'معالجة المرتجعات'],
            ['href' => '/returns/supplier', 'icon' => 'truck-loading', 'text' => 'إرسال المرتجعات للموردين'],
        ],
    ],
    [
        'href' => '/suppliers',
        'icon' => 'address-book',
        'text' => 'إدارة الموردين',
        'children' => [
            ['href' => '/suppliers/add', 'icon' => 'user-plus', 'text' => 'إضافة وتحديث بيانات الموردين'],
        ],
    ],
    [
        'href' => '/users',
        'icon' => 'users',
        'text' => 'إدارة المستخدمين',
        'children' => [
            ['href' => '/users/manage', 'icon' => 'user-edit', 'text' => 'إدارة بيانات المستخدمين'],
            ['href' => '/users/permissions', 'icon' => 'key', 'text' => 'منح الأذونات والصلاحيات'],
            ['href' => '/users/activity', 'icon' => 'chart-pie', 'text' => 'مراقبة نشاط المستخدمين'],
        ],
    ],
    [
        'href' => '/tracking',
        'icon' => 'list',
        'text' => 'تتبع الكميات',
        'children' => [
            ['href' => '/tracking/warehouses', 'icon' => 'warehouse', 'text' => 'تتبع الكميات في المستودعات والمحلات'],
            ['href' => '/tracking/update', 'icon' => 'sync', 'text' => 'تحديث الكميات بناءً على الشحنات والمبيعات'],
        ],
    ],
    [
        'href' => '/reports',
        'icon' => 'chart-bar',
        'text' => 'إنشاء التقارير',
        'children' => [
            ['href' => '/reports/inventory', 'icon' => 'clipboard-list', 'text' => 'تقارير المخزون'],
            ['href' => '/reports/shipments', 'icon' => 'shipping-fast', 'text' => 'تقارير الشحنات'],
            ['href' => '/reports/invoices', 'icon' => 'file-alt', 'text' => 'تقارير الفواتير'],
        ],
    ],
    [
        'href' => '/customers-stores',
        'icon' => 'store',
        'text' => 'إدارة العملاء والمحلات',
        'children' => [
            ['href' => '/customers-stores/add', 'icon' => 'address-card', 'text' => 'إضافة وتحديث بيانات العملاء والمحلات'],
            ['href' => '/customers-stores/orders', 'icon' => 'shopping-cart', 'text' => 'إدارة الطلبات من العملاء'],
            ['href' => '/customers-stores/inventory', 'icon' => 'boxes', 'text' => 'إدارة المخزون في المحلات'],
        ],
    ],
    [
        'href' => '/representatives',
        'icon' => 'user-tie',
        'text' => 'إدارة مندوبين المحلات',
        'children' => [
            ['href' => '/representatives/add', 'icon' => 'user-plus', 'text' => 'إضافة وتحديث بيانات المندوبين'],
            ['href' => '/representatives/tasks', 'icon' => 'tasks', 'text' => 'تتبع المهام'],
            ['href' => '/representatives/communication', 'icon' => 'comments', 'text' => 'إدارة التواصل بين المحلات والمستودعات'],
        ],
    ],
];

@endphp
<header class="flex flex-col lg:grid lg:grid-rows-[auto_1fr] h-full bg-white dark:bg-gray-900 dark:text-white">
    <nav class="fixed top-0 z-50 w-full border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center">
                <div class="flex items-center justify-start rtl:justify-end">
                    <x-logo href="/" showText="true" />
                </div>
                <div class="flex items-center space-x-4 ms-auto rtl:space-x-reverse">
                    <button id="theme-toggle" type="button" class="text-gray-900 dark:text-white">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="relative">
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only text-gray-900 dark:text-white">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                   
                    </div>
                    <button id="sidebar-toggle" class="text-gray-900 dark:text-white">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <!-- Sidebar -->
    <aside id="sidebar" 
    class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 lg:translate-x-0">
        <div class="h-[3.13rem]">
            <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
            <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:hover:text-white text-slate-200" href="javascript:;" target="_blank">
                <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">مدار</span>
            </a>
        </div>
    
        <div class="items-center block w-auto max-h-screen overflow-auto h-[calc(127vh-370px)] grow basis-full">
            <ul class="flex flex-col pl-0 mb-0">
                @foreach ($sidebarLinks as $link)
                    <li class="mb-2">
                        <a href="{{ $link['href'] }}" class="flex items-center p-2 text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-slate-300">
                            <i class="fas fa-{{ $link['icon'] }} text-base ml-3"></i>
                            <span class="ml-4 sidebar-text">{{ $link['text'] }}</span>
                        </a>
                        @if (isset($link['children']))
                            <ul class="pl-4 mt-2 space-y-2">
                                @foreach ($link['children'] as $child)
                                    <li>
                                        <a href="{{ $child['href'] }}" class="flex items-center p-2 text-slate-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-slate-400">
                                            <i class="fas fa-{{ $child['icon'] }} text-sm ml-3"></i>
                                            <span class="sidebar-text">{{ $child['text'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                @if ($role)
                <li>
                    {{-- <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="ml-3 hidden sm:block">Logout</span>
                        </button>
                    </form> --}}
                </li>
                @endif
            </ul>
        </div>
    </aside>
   
    
    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarText = document.querySelectorAll('.sidebar-text');

        // التحكم في إظهار وإخفاء القائمة الجانبية في العرض الصغير
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth < 1024) { // العرض الصغير
                sidebar.style.width = "16rem"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
                sidebar.classList.toggle('-translate-x-full'); // تبديل حالة الإخفاء/الإظهار
                sidebarText.forEach(el => el.style.display = 'inline'); // إظهار النصوص
            }
        });

        // مراقبة التغيرات في حجم الشاشة
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) { // العرض الكبير
                sidebar.classList.remove('-translate-x-full'); // إظهار القائمة الجانبية
                 sidebar.style.width = "16rem"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
                sidebarText.forEach(el => el.style.display = 'inline'); // إظهار النصوص
            } else {
                // sidebar.classList.add('-translate-x-full'); // إخفاء القائمة الجانبية
                sidebar.style.width = "50px"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
                sidebarText.forEach(el => el.style.display = 'none'); // إخفاء النصوص
            }
        });

        // إخفاء النصوص عند العرض الصغير
        window.addEventListener('load', () => {
            if (window.innerWidth < 1024) {
                sidebar.style.width = "50px"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
                sidebarText.forEach(el => el.style.display = 'none'); // إخفاء النصوص

            }
            else{
                if (window.innerWidth >= 1024) { // العرض الكبير
                sidebar.classList.remove('-translate-x-full'); // إظهار القائمة الجانبية
                 sidebar.style.width = "16rem"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
                sidebarText.forEach(el => el.style.display = 'inline'); // إظهار النصوص
            }
        }});
    </script>
</header>