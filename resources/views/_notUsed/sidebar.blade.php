@php
use App\Models\User;

$user = Auth::user();
$role = $user ? $user->roles()->first() : null;
 
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
            ['href' => '/supplier-orders/track', 'icon' => 'tasks', 'text' => 'متابعة حالة الطلبات'],
            ['href' => '/supplier-orders/validate', 'icon' => 'check-circle', 'text' => 'التأكد من الكميات المطلوبة'],
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
        // 'children' => [
        //     ['href' => '/suppliers/add', 'icon' => 'user-plus', 'text' => 'إضافة وتحديث بيانات الموردين'],
        // ],
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
<header class="flex flex-col lg:grid lg:grid-rows-[auto_1fr] h-full bg-white dark:bg-gray-900 dark:text-white"

>
    <x-navbar />    
    <!-- Sidebar -->
 <!-- Button to open the sidebar -->
<button id="sidebar-toggle" class="fixed top-[5rem] right-0 z-50 p-2 text-white bg-blue-500 rounded-full shadow-md lg:hidden">
    <i class="fas fa-bars"></i>
</button>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-[5rem] right-0 w-16 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 bg-white text-white dark:bg-gray-800 dark:text-white">
        <div class="items-center block w-auto max-h-screen overflow-auto h-[calc(127vh-370px)] grow basis-full">
            <ul class="flex flex-col pl-0 mb-0">
                @foreach ($sidebarLinks as $link)
                    <li class="mb-2">
                        <a href="javascript:void(0);" 
                           class="flex items-center justify-center p-2 text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-slate-300"
                           onclick="toggleChildren('{{ $link['text'] }}')">
                            <i class="fas fa-{{ $link['icon'] }} text-base"></i>
                        </a>
                        <!-- This will hold the child links -->
                        <div id="children-{{ $link['text'] }}" class="pl-4 mt-2 space-y-2 hidden">
                            @foreach ($link['children'] as $child)
                                <a href="{{ $child['href'] }}" class="flex items-center justify-center p-2 text-slate-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-slate-400">
                                    <i class="fas fa-{{ $child['icon'] }} text-sm"></i>
                                    <span class="ml-2">{{ $child['text'] }}</span>
                                </a>
                            @endforeach
                            <button onclick="closeChildren('{{ $link['text'] }}')" class="mt-2 px-4 py-2 bg-red-500 text-white rounded">
                                إغلاق
                            </button>
                        </div>
                    </li>
                @endforeach
                @if ($role)
                    <li>
                        {{-- Logout form or button can go here --}}
                    </li>
                @endif
            </ul>
        </div>
    </aside>
</header>

<!-- Add a script to toggle the sidebar -->
<script>
    // Function to show or hide the children links
    function toggleChildren(linkText) {
        const childrenDiv = document.getElementById('children-' + linkText);
        childrenDiv.classList.toggle('hidden');
    }

    // Function to close the children links
    function closeChildren(linkText) {
        const childrenDiv = document.getElementById('children-' + linkText);
        childrenDiv.classList.add('hidden');
    }

    // Sidebar toggle functionality
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('transform');
        sidebar.classList.toggle('-translate-x-full');
    });
</script>
