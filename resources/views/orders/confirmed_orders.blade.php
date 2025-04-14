<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'التحقق من الطلبات المؤكدة'"></x-title>
        
        <div class="w-full mt-5 bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">معلومات الطلبات</h2>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-100 p-3 rounded">
                    <p class="font-bold">إجمالي الطلبات: {{ $allOrders->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded">
                    <p class="font-bold">عدد الطلبات المعلقة: {{ $pendingCount }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded">
                    <p class="font-bold">عدد الطلبات المؤكدة: {{ $confirmedCount }}</p>
                </div>
            </div>
            
            <!-- فلترة الطلبات حسب النوع -->
            <div class="mt-4">
                <h3 class="text-lg font-bold mb-2">تصفية الطلبات:</h3>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('orders.check-confirmed', ['type' => 'all']) }}" class="px-4 py-2 rounded {{ $type === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                        جميع الطلبات
                    </a>
                    <a href="{{ route('orders.check-confirmed', ['type' => 'buy']) }}" class="px-4 py-2 rounded {{ $type === 'buy' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                        طلبات الشراء
                    </a>
                    <a href="{{ route('orders.check-confirmed', ['type' => 'sell']) }}" class="px-4 py-2 rounded {{ $type === 'sell' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                        طلبات البيع
                    </a>
                </div>
            </div>
        </div>
        
        <div class="w-full mt-5 overflow-x-auto">
            @if($orders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    <p class="font-bold">لا توجد طلبات مؤكدة حالياً.</p>
                    <p class="mt-2">تم تحديث جميع الطلبات المعلقة إلى مؤكدة. يرجى تحديث الصفحة.</p>
                </div>
            @else
                <h2 class="text-xl font-bold mb-4">الطلبات المؤكدة</h2>
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الطلب</th>
                            <th class="py-2 px-4 border-b">النوع</th>
                            <th class="py-2 px-4 border-b">الحالة</th>
                            <th class="py-2 px-4 border-b">رقم أمر الطلب</th>
                            <th class="py-2 px-4 border-b">المورد/العميل</th>
                            <th class="py-2 px-4 border-b">الفرع</th>
                            <th class="py-2 px-4 border-b">التاريخ</th>
                            <th class="py-2 px-4 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">{{ $order->id }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->type == 'buy' ? 'شراء' : 'بيع' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->status }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->purchase_order_number }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->partner->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->branch->name ?? 'غير محدد' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <div class="flex justify-center space-x-2 space-x-reverse">
                                        @if($order->type == 'buy')
                                            <a href="{{ route('purchase-orders.create', $order->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                <i class="fas fa-file-invoice mr-1"></i> تكوين أمر شراء
                                            </a>
                                            <a href="{{ route('orders.print-purchase-order', $order->id) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" target="_blank">
                                                <i class="fas fa-print mr-1"></i> طباعة أمر الشراء
                                            </a>
                                            <a href="{{ route('invoices.create-from-order', $order->id) }}" class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600">
                                                <i class="fas fa-file-invoice-dollar mr-1"></i> إنشاء فاتورة شراء
                                            </a>
                                        @else
                                            <a href="{{ route('sales-orders.create', $order->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                <i class="fas fa-file-invoice mr-1"></i> تكوين أمر صرف
                                            </a>
                                            <a href="{{ route('orders.print-sales-order', $order->id) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" target="_blank">
                                                <i class="fas fa-print mr-1"></i> طباعة أمر الصرف
                                            </a>
                                            <a href="{{ route('invoices.create-from-order', $order->id) }}" class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600">
                                                <i class="fas fa-file-invoice-dollar mr-1"></i> إنشاء فاتورة بيع
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <div class="w-full mt-8">
            <h2 class="text-xl font-bold mb-4">جميع الطلبات</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">رقم الطلب</th>
                        <th class="py-2 px-4 border-b">النوع</th>
                        <th class="py-2 px-4 border-b">الحالة</th>
                        <th class="py-2 px-4 border-b">رقم أمر الطلب</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allOrders as $order)
                        <tr class="{{ $order->status == 'confirmed' ? 'bg-green-100' : ($order->status == 'pending' ? 'bg-yellow-100' : '') }}">
                            <td class="py-2 px-4 border-b text-center">{{ $order->id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $order->type == 'buy' ? 'شراء' : 'بيع' }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $order->status }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $order->purchase_order_number ?? 'غير محدد' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="w-full mt-8 flex justify-between">
            <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                العودة إلى قائمة الطلبات
            </a>
            <a href="{{ route('orders.pending-approval') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                الذهاب إلى الطلبات المعلقة
            </a>
        </div>
    </section>
</x-layout>
