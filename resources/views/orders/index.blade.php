<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إدارة الطلبات'"></x-title>
        <!-- فلترة الطلبات -->
        <form method="GET" action="{{ route('orders.index') }}" class="w-full flex justify-between items-center mt-5">
            <x-search-input id="search-orders" name="search" placeholder="ابحث عن الطلبات" :value="request()->input('search')" class="w-1/3 p-2 border border-gray-300 rounded-md shadow-sm" />
            <div class="flex">
                <x-button :href="route('orders.pending-approval')" type="button" class="ml-2 bg-yellow-500 text-white p-2 rounded-md shadow-md hover:bg-yellow-600">
                    <i class="fas fa-clipboard-list mr-2"></i> طلبات الشراء المعلقة
                </x-button>
                <x-button :href="route('orders.check-confirmed')" type="button" class="ml-2 bg-green-500 text-white p-2 rounded-md shadow-md hover:bg-green-600">
                    <i class="fas fa-clipboard-check mr-2"></i> طلبات الشراء المؤكدة
                </x-button>
                <x-button :href="route('orders.create')" type="button" class="ml-2 bg-blue-500 text-white p-2 rounded-md shadow-md hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i> إضافة طلب جديد
                </x-button>
            </div>
        </form>
        <!-- جدول عرض الطلبات -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4 w-full">
            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="p-4">رقم الطلب</th>
                        <th class="px-6 py-3">نوع الطلب</th>
                        <th class="px-6 py-3">حالة الطلب</th>
                        <th class="px-6 py-3">المنتج</th>
                        <th class="px-6 py-3">طريقة الدفع</th>
                        <th class="px-6 py-3">الفرع</th>
                        <th class="px-6 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="bg-gray-200 border-b hover:bg-gray-300">
                            <td class="p-4">{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ $order->type == 'buy' ? 'شراء' : 'بيع' }}</td>
                            <td class="px-6 py-4">{{ $order->status }}</td>
                            @foreach($order->order_details as $detail)
                            <td class="px-6 py-4">{{ $detail->product->name ?? 'لايوجد' }}</td>
                            @endforeach
                            <td class="px-6 py-4">{{ $order->paymentType->name ?? 'لايوجد' }}</td>
                            <td class="px-6 py-4">{{ $order->branch->name ?? 'لايوجد' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-600">تعديل</a>
                                {{-- <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">حذف</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- إضافة pagination -->
        <div class="mt-5">
            {{ $orders->links() }}
        </div>
    </section>
</x-layout>
