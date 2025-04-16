<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="$type === 'buy' ? 'طلبات الشراء المعلقة للموافقة' : 'طلبات البيع المعلقة للموافقة'"></x-title>

        <!-- أزرار تصفية الطلبات -->
        <div class="w-full mt-4">
            <h3 class="text-lg font-bold mb-2">تصفية الطلبات:</h3>
            <div class="flex space-x-2 space-x-reverse">
                <a href="{{ route('orders.pending-approval', ['type' => 'buy']) }}" class="px-4 py-2 rounded {{ $type === 'buy' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    طلبات الشراء
                </a>
                <a href="{{ route('orders.pending-approval', ['type' => 'sell']) }}" class="px-4 py-2 rounded {{ $type === 'sell' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    طلبات البيع
                </a>
            </div>
        </div>

        <!-- جدول الطلبات المعلقة -->
        <div class="w-full mt-5 overflow-x-auto">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    لا توجد طلبات {{ $type === 'buy' ? 'شراء' : 'بيع' }} معلقة للموافقة حالياً.
                </div>
            @else
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الطلب</th>
                            <th class="py-2 px-4 border-b">التاريخ</th>
                            <th class="py-2 px-4 border-b">الفرع</th>
                            <th class="py-2 px-4 border-b">{{ $type === 'buy' ? 'المورد' : 'العميل' }}</th>
                            <th class="py-2 px-4 border-b">إجمالي المنتجات</th>
                            <th class="py-2 px-4 border-b">إجمالي المبلغ</th>
                            <th class="py-2 px-4 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">{{ $order->id }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->branch->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->partner->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->order_details->count() }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    {{ $order->order_details->sum(function($detail) { return $detail->quantity * $detail->price; }) }}
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    <div class="flex justify-center space-x-2 rtl:space-x-reverse">
                                        <!-- زر عرض تفاصيل الطلب -->
                                        <a href="{{ route('orders.edit', $order->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            عرض
                                        </a>
                                        
                                        <!-- زر الموافقة على الطلب -->
                                        <form action="{{ route('orders.approve', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                موافقة
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
