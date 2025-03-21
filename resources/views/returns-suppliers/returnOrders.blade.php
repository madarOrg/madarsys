<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'تتبع طلبات المرتجعات للموردين'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('returns_process.index') }}">
            <x-search-input id="search-return-orders" name="search" placeholder="ابحث عن المرتجعات"
                :value="request()->input('search')" />
        </form>
    </section>

    <!-- جدول عرض المرتجعات الواردة -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">#</th>
                    <th class="px-6 py-3">رقم الطلب</th>
                    <th class="px-6 py-3">اسم المورد</th>
                    <th class="px-6 py-3"> حالة الطلب</th>
                    <th class="px-6 py-3">التاريخ</th>
                    <th class="px-6 py-3">طباعة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returnOrders as $order)
                                @php
                                    // تحديد اللون بناءً على حالة المنتج
                                    $statusColor = '';
                                    switch ($order->status) {
                                        
                                        case 'مرفوض':
                                            $statusColor = 'bg-yellow-500'; // اللون الأصفر
                                            break;
                                            case 'قيد التوصيل':
                                            $statusColor = 'bg-blue-500'; // اللون الأزرق
                                            break;
                                        case 'تم الاستلام':
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
                                    <td class="px-6 py-4">{{ $order->supplier->name }}</td>
                                    <td class="px-6 py-4">{{ $order->status }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->return_date)->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('return_suppliers.generatePdf', $order->id) }}" class="btn btn-primary">
                                            <i class="fas fa-download"></i> <!-- أيقونة التحميل -->
                                        </a>
                                    </td>

                                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <x-pagination-links :paginator="$returnOrders" />
</x-layout>