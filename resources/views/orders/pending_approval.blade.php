<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="$type === 'buy' ? 'طلبات الشراء المعلقة للموافقة' : 'طلبات البيع المعلقة للموافقة'"></x-title>

        <!-- أزرار تصفية الطلبات -->
        <div class="w-full mt-4">
            <h3 class="text-lg font-bold mb-2">تصفية الطلبات:</h3>
            {{-- <div class="flex space-x-2 space-x-reverse">
                <a href="{{ route('orders.pending-approval', ['type' => 'buy']) }}" class="px-4 py-2 rounded {{ $type === 'buy' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    طلبات الشراء
                </a>
                <a href="{{ route('orders.pending-approval', ['type' => 'sell']) }}" class="px-4 py-2 rounded {{ $type === 'sell' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    طلبات البيع
                </a>
            </div> --}}

            <x-button :href="route('orders.pending-approval', ['type' => 'buy'])"
                class="{{ $type === 'buy' ? 'bg-blue-500 text-white' : ' text-gray-700' }}">
                طلبات الشراء
            </x-button>

            <x-button :href="route('orders.pending-approval', ['type' => 'sell'])"
                class="{{ $type === 'sell' ? 'bg-blue-500 text-white' : ' text-gray-700' }}">
                طلبات البيع
            </x-button>

        </div>

    

        <!-- جدول الطلبات المعلقة -->
        <div class="w-full mt-5 overflow-x-auto">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    لا توجد طلبات {{ $type === 'buy' ? 'شراء' : 'بيع' }} معلقة للموافقة حالياً.
                </div>
            @else
                <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="">رقم الطلب</th>
                            <th class="py-2 px-4 ">التاريخ</th>
                            <th class="py-2 px-4 ">الفرع</th>
                            <th class="py-2 px-4 ">{{ $type === 'buy' ? 'المورد' : 'العميل' }}</th>
                            <th class="py-2 px-4 ">إجمالي المنتجات</th>
                            <th class="py-2 px-4 ">إجمالي المبلغ</th>
                            <th class="py-2 px-4 ">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr
                                class="bg-gray-200  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">

                                <td class="py-2 px-2">{{ $order->id }}</td>
                                <td class="py-2 px-4  text-center">{{ $order->created_at->format('Y-m-d') }}
                                </td>
                                <td class="py-2 px-4  text-center">{{ $order->branch->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4  text-center">{{ $order->partner->name ?? 'غير محدد' }}
                                </td>
                                <td class="py-2 px-4  text-center">{{ $order->order_details->count() }}</td>
                                <td class="py-2 px-4  text-center">
                                    {{ $order->order_details->sum(function ($detail) {return $detail->quantity * $detail->price;}) }}
                                </td>
                                <td class="py-2 px-4  text-center">
                                    <div class="flex justify-center space-x-2 rtl:space-x-reverse">
                                        <!-- زر عرض تفاصيل الطلب -->
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"> <i
                                            class="fas fa-eye"></i>                                      
                                        </a>

                                        <!-- زر الموافقة على الطلب -->
                                        <form action="{{ route('orders.approve', $order->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
    class="flex items-center gap-2 text-green-500  px-3 py-1 rounded hover:text-green-600">
    <i class="fa-solid fa-thumbs-up"></i>
    
</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ترقيم الصفحات -->
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layout>
