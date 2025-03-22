<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'قائمة المرتجعات الواردة'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('returns_process.index') }}">
            <x-search-input id="search-return-orders" name="search" placeholder="ابحث عن المرتجعات" :value="request()->input('search')" />
        </form>
    </section>


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
                    <th class="px-6 py-3">تفاصيل المرتجع</th>
                </tr>
            </thead>
            <tbody>
            @foreach($returnOrders as $order)
    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
        <td class="p-4">{{ $order->id }}</td>
        <td class="px-6 py-4">{{ $order->return_number }}</td>
        <td class="px-6 py-4">{{ $order->customer->name }}</td>
        <td class="px-6 py-4">{{ $order->return_reason }}</td>
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->return_date)->format('Y-m-d') }}</td>

        <td class="px-2 py-2 flex space-x-2">
        <x-button href="{{ route('returns_process.show', $order->id) }}" class="text-yellow-600 hover:underline">
        <i class="fas fa-eye"></i>
        </x-button>
        </td>
    </tr>
@endforeach

            </tbody>
        </table>
    </div>

    <x-pagination-links :paginator="$returnOrders" />
</x-layout>
