<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'أوامر الشراء المعتمدة'"></x-title>
        
        <div class="w-full mt-5 bg-white p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">قائمة أوامر الشراء المعتمدة</h2>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('purchase-orders.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-list ml-1"></i> جميع أوامر الشراء
                    </a>
                </div>
            </div>
            
            @if($purchaseOrders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    <p class="font-bold">لا توجد أوامر شراء معتمدة حالياً.</p>
                    <p class="mt-2">يمكنك اعتماد أوامر الشراء من خلال الضغط على زر "اعتماد" في صفحة تفاصيل أمر الشراء.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b">رقم أمر الشراء</th>
                                <th class="py-2 px-4 border-b">رقم الطلب</th>
                                <th class="py-2 px-4 border-b">المورد</th>
                                <th class="py-2 px-4 border-b">الحالة</th>
                                <th class="py-2 px-4 border-b">تاريخ الإصدار</th>
                                <th class="py-2 px-4 border-b">تاريخ التسليم المتوقع</th>
                                <th class="py-2 px-4 border-b">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrders as $purchaseOrder)
                                <tr>
                                    <td class="py-2 px-4 border-b text-center">{{ $purchaseOrder->order_number }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $purchaseOrder->order_id }}</td>
                                    <td class="py-2 px-4 border-b">{{ $purchaseOrder->partner->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">معتمد</span>
                                    </td>
                                    <td class="py-2 px-4 border-b text-center">{{ $purchaseOrder->issue_date }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $purchaseOrder->expected_delivery_date ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <div class="flex justify-center space-x-1 space-x-reverse">
                                            <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('invoices.create-from-purchase-order', $purchaseOrder->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                                <i class="fas fa-file-invoice"></i> إنشاء فاتورة
                                            </a>
                                            
                                            <a href="{{ route('purchase-orders.print', $purchaseOrder->id) }}" class="bg-purple-500 text-white px-2 py-1 rounded hover:bg-purple-600" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</x-layout>
