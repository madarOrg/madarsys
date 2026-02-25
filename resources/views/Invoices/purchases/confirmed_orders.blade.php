<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'طلبات الشراء المؤكدة'"></x-title>
        
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        
        <div class="w-full mt-5 overflow-x-auto">
            @if($orders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    لا توجد طلبات شراء مؤكدة حالياً.
                </div>
            @else
            <table class=" w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                            <th class="py-2 px-4 border-b">رقم الطلب</th>
                            <th class="py-2 px-4 border-b">رقم أمر الشراء</th>
                            <th class="py-2 px-4 border-b">التاريخ</th>
                            <th class="py-2 px-4 border-b">الفرع</th>
                            <th class="py-2 px-4 border-b">المورد/الشريك</th>
                            <th class="py-2 px-4 border-b">إجمالي المنتجات</th>
                            <th class="py-2 px-4 border-b">إجمالي المبلغ</th>
                            <th class="py-2 px-4 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr  class="item-row bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <td class="py-2 px-4 border-b text-center">{{ $order->id }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->purchase_order_number }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->branch->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->partner->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->order_details->count() }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    {{ $order->order_details->sum(function($detail) { return $detail->quantity * $detail->price; }) }}
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    <div class="flex justify-center space-x-2 space-x-reverse">
                                        {{-- <a href="{{ route('invoices.create-from-order', $order->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            إنشاء فاتورة
                                        </a>
                                        <a href="{{ route('orders.print-purchase-order', $order->id) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" target="_blank">
                                            طباعة أمر الشراء
                                        </a> --}}
                                        <a href="{{ route('invoices.create-from-order', $order->id) }}" class="bg-green-100 text-gray-600 px-3 py-1 rounded hover:bg-green-100">
                                            <i class="fas fa-file-invoice-dollar mr-1"></i> إنشاء فاتورة 
                                        </a>
                                        <a href="{{ route('orders.print-purchase-order', $order->id) }}" class="text-gray-600  px-3 py-1 rounded " target="_blank">
                                            <i class="fas fa-print mr-1 text-xl"></i>   
                                        </a>
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
