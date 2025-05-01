<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'قائمة مرتجعات الموردين'"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <!-- زر إنشاء مرتجع جديد -->
            <x-button href="{{ route('returns-suppliers.create') }}" class="">
                <i class="fas fa-plus ml-1"></i> إنشاء مرتجع مورد جديد
            </x-button>
            
            <!-- زر تقارير المرتجعات -->
            <x-button href="{{ route('returns.reports.supplier') }}" class="">
                <i class="fas fa-chart-bar ml-1"></i> تقارير مرتجعات الموردين
            </x-button>
        </div>
    </section>

    <div class="flex items-center justify-between mt-4">
        <!-- مربع البحث -->
        <form method="GET" action="{{ route('returns-suppliers.index') }}" class="w-1/3">
            <x-search-input id="search-return-orders" name="search" placeholder="ابحث عن المرتجعات (رقم، مورد، سبب)" :value="request()->input('search')" />
        </form>
        
        <!-- تصفية حسب الحالة -->
        <div class="flex items-center space-x-2 space-x-reverse">
            <span class="text-gray-700">تصفية حسب الحالة:</span>
            <a href="{{ route('returns-suppliers.index') }}" class="px-3 py-1 {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-blue-500 hover:text-white">الكل</a>
    <a href="{{ route('returns-suppliers.index', ['status' => 'قيد المراجعة']) }}" class="px-3 py-1 {{ request('status') == 'قيد المراجعة' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-yellow-400 hover:text-white">قيد المراجعة</a>
    <a href="{{ route('returns-suppliers.index', ['status' => 'قيد التوصيل']) }}" class="px-3 py-1 {{ request('status') == 'قيد التوصيل' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-blue-500 hover:text-white">قيد التوصيل</a>
    <a href="{{ route('returns-suppliers.index', ['status' => 'تم الاستلام']) }}" class="px-3 py-1 {{ request('status') == 'تم الاستلام' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-green-500 hover:text-white">تم الاستلام</a>
</div>
    </div>

    <!-- جدول عرض مرتجعات الموردين -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">#</th>
                    {{-- <th class="px-6 py-3">رقم المرتجع</th> --}}
                    <th class="px-6 py-3">اسم المورد</th>
                    <th class="px-6 py-3">سبب الإرجاع</th>
                    <th class="px-6 py-3">تاريخ الإنشاء</th>
                    <th class="px-6 py-3">الحالة</th>
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($returnOrders as $order)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <td class="p-4">{{ $order->id }}</td>
                    {{-- <td class="px-6 py-4">{{ $order->return_number }}</td> --}}
                    <td class="px-6 py-4">{{ $order->supplier->name ?? 'غير محدد' }}</td>
                    <td class="px-6 py-4">{{ Str::limit($order->return_reason, 30) }}</td>
                    <td class="px-6 py-4">{{ $order->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        @if($order->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                        @elseif($order->status == 'sent')
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">تم الإرسال</span>
                        @elseif($order->status == 'completed')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                        @elseif($order->status == 'cancelled')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">ملغي</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $order->status }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex space-x-2 space-x-reverse">
                        {{-- <x-button href="{{ route('returns-suppliers.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md">
                            <i class="fas fa-eye"></i>
                        </x-button>
                        
                        @if($order->status == 'pending')
                            <x-button href="{{ route('returns-suppliers.edit', $order->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            
                            <form action="{{ route('returns-suppliers.send', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من إرسال هذا المرتجع للمورد؟');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        @endif
                        
                        <x-button href="{{ route('returns-suppliers.print', $order->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white p-2 rounded-md" target="_blank">
                            <i class="fas fa-print"></i>
                        </x-button>
                        
                        @if($order->status != 'completed')
                            <form action="{{ route('returns-suppliers.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المرتجع؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif --}}
                        <a href="{{ route('returns-suppliers.show', $order->id) }}" class="text-blue-500 hover:text-blue-600  p-2 rounded-md inline-block">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if($order->status == 'pending')
                            <a href="{{ route('returns-suppliers.edit', $order->id) }}" class="text-yellow-500 hover:text-yellow-600  p-2 rounded-md inline-block">
                                <i class="fas fa-edit"></i>
                            </a>
                        
                            <a href="#" onclick="event.preventDefault(); if(confirm('هل أنت متأكد من إرسال هذا المرتجع للمورد؟')) { document.getElementById('send-return-{{ $order->id }}').submit(); }" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md inline-block">
                                <i class="fas fa-paper-plane"></i>
                            </a>
                            <form id="send-return-{{ $order->id }}" action="{{ route('returns-suppliers.send', $order->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('PUT')
                            </form>
                        @endif
                        <a href="{{ route('returns-suppliers.edit', $order->id) }}" class="text-yellow-500 hover:text-yellow-600 p-2 rounded-md">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <a href="{{ route('returns-suppliers.print', $order->id) }}" class="text-purple-500 hover:text-purple-600  p-2 rounded-md inline-block" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        
                        @if($order->status != 'completed')
                            <a href="#" onclick="event.preventDefault(); if(confirm('هل أنت متأكد من حذف هذا المرتجع؟')) { document.getElementById('delete-return-{{ $order->id }}').submit(); }" class="text-red-500 hover:text-red-600  p-2 rounded-md inline-block">
                                <i class="fas fa-trash"></i>
                            </a>
                            <form id="delete-return-{{ $order->id }}" action="{{ route('returns-suppliers.destroy', $order->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                        
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">لا توجد مرتجعات موردين متاحة</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <x-pagination-links :paginator="$returnOrders" />
    </div>
</x-layout>
