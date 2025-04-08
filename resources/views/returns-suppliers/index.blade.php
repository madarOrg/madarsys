<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إرسال المرتجعات للموردين'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('returns_process.index') }}">
            <x-search-input id="search-return-orders" name="search" placeholder="ابحث عن المرتجعات"
                :value="request()->input('search')" />
        </form>
    </section>

     <!-- زر عرض طلبات الإرجاع -->
     <x-button href="{{ route('return_suppliers.ordersendToSupplier') }}" type="button">
        <i class="fas fa-eye mr-2"></i> عرض طلبات الإرجاع
    </x-button>

    <!-- زر طلب إرجاع -->
    <x-button href="{{ route('return_suppliers.create') }}" type="button">
        <i class="fas fa-plus mr-2"></i> طلب إرجاع
    </x-button>
   
    <!-- جدول عرض المرتجعات الواردة -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">#</th>
                    <th class="px-6 py-3">رقم المرتجع</th>
                    <th class="px-6 py-3">اسم المورد</th>
                    <th class="px-6 py-3">اسم المنتج</th>
                    <th class="px-6 py-3"> حالة المنتج في قائمة الارتجاع</th>
                    <th class="px-6 py-3">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returnOrders as $order)
                                @php
                                    // تحديد اللون بناءً على حالة المنتج
                                    $statusColor = '';
                                    switch ($order->status) {
                                        case 'تم ارسال المنتج في طلب ارجاع':
                                            $statusColor = 'bg-green-500'; // اللون الأخضر
                                            break;
                                        default:
                                            $statusColor = 'bg-gray-500'; // اللون الافتراضي
                                    }
                                @endphp
                                <tr
                                    class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <td> <span class="inline-block {{ $statusColor }} w-4 h-4 rounded-full mr-2"></span></td>
                                    <td class="px-6 py-4">{{ $order->id  }}</td>
                                    <td class="px-6 py-4">{{ $order->returnSuppliersOrder->supplier->name ?? 'غير معروف' }}</td>
                                    <td class="px-6 py-4">{{ $order->product->name ?? 'غير معروف'}}</td>
                                    <td class="px-6 py-4">{{ $order->status }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->return_date)->format('Y-m-d') }}</td>
                                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <x-pagination-links :paginator="$returnOrders" />
</x-layout>