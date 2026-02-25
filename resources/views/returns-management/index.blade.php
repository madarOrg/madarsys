<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'قائمة المرتجعات الواردة'"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <!-- زر إنشاء مرتجع جديد -->
            <x-button href="{{ route('returns-management.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus ml-1"></i> إنشاء مرتجع جديد
            </x-button>
            
            <!-- زر تقارير المرتجعات -->
            <x-button href="{{ route('returns-management.reports') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-chart-bar ml-1"></i> تقارير المرتجعات
            </x-button>
        </div>
    </section>

    <div class="flex items-center justify-between mt-4">
        <!-- مربع البحث -->
        <form method="GET" action="{{ route('returns-management.index') }}" class="w-1/3">
            <x-search-input id="search-return-orders" name="search" placeholder="ابحث عن المرتجعات (رقم، عميل، سبب)" :value="request()->input('search')" />
        </form>
        
        <!-- تصفية حسب الحالة -->
        <div class="flex items-center space-x-2 space-x-reverse">
            <span class="text-gray-700">تصفية حسب الحالة:</span>
            <a href="{{ route('returns-management.index') }}" class="px-3 py-1 {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-blue-500 hover:text-white">الكل</a>

            <a href="{{ route('returns-management.index', ['status' => 'معلق']) }}" class="px-3 py-1 {{ request('status') == 'معلق' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-yellow-400 hover:text-white">معلق</a>
            
            <a href="{{ route('returns-management.index', ['status' => 'مكتمل']) }}" class="px-3 py-1 {{ request('status') == 'مكتمل' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-green-500 hover:text-white">مكتمل</a>
            
            <a href="{{ route('returns-management.index', ['status' => 'ملغي']) }}" class="px-3 py-1 {{ request('status') == 'ملغي' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-red-500 hover:text-white">ملغي</a>
                
        </div>
    </div>

    <!-- جدول عرض المرتجعات الواردة -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">#</th>
                    <th class="px-6 py-3">رقم المرتجع</th>
                    <th class="px-6 py-3">اسم العميل</th>
                    <th class="px-6 py-3">سبب الإرجاع</th>
                    <th class="px-6 py-3">التاريخ</th>
                    <th class="px-6 py-3">الحالة</th>
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($returnOrders as $order)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <td class="p-4">{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->return_number }}</td>
                    <td class="px-6 py-4">{{ $order->customer->name ?? 'غير محدد' }}</td>
                    <td class="px-6 py-4">{{ Str::limit($order->return_reason, 30) }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->return_date)->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        @if($order->status == 'معلق')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                        @elseif($order->status == 'مكتمل')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                        @elseif($order->status == 'ملغي')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">ملغي</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $order->status }}</span>
                        @endif
                    </td>
                    {{-- <td>{{ $order->status }}</td> --}}
                    <td class="px-6 py-4 flex space-x-2 space-x-reverse">
                        <a href="{{ route('returns-management.show', $order->id) }}" class="text-blue-500 hover:text-blue-600 p-2 rounded-md">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('returns-management.edit', $order->id) }}" class="text-yellow-500 hover:text-yellow-600 p-2 rounded-md">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('returns-management.print', $order->id) }}" class="text-purple-500 hover:text-purple-600  p-2 rounded-md inline-block" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        <form action="{{ route('returns-management.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المرتجع؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600  p-2 rounded-md">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">لا توجد مرتجعات متاحة</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <x-pagination-links :paginator="$returnOrders" />
    </div>
</x-layout>
