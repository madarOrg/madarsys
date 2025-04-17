<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'أوامر الصرف المعتمدة'"></x-title>
        
        <div class="w-full mt-5 bg-white p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">قائمة أوامر الصرف المعتمدة</h2>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('sales-orders.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-list ml-1"></i> جميع أوامر الصرف
                    </a>
                </div>
            </div>
            
            @if($salesOrders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    <p class="font-bold">لا توجد أوامر صرف معتمدة حالياً.</p>
                    <p class="mt-2">يمكنك اعتماد أوامر الصرف من خلال الضغط على زر "اعتماد" في صفحة تفاصيل أمر الصرف.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 ">رقم أمر الصرف</th>
                                <th class="py-2 px-4 ">رقم الطلب</th>
                                <th class="py-2 px-4 ">العميل</th>
                                <th class="py-2 px-4 ">الحالة</th>
                                <th class="py-2 px-4 ">تاريخ الإصدار</th>
                                <th class="py-2 px-4 ">تاريخ التسليم المتوقع</th>
                                <th class="py-2 px-4 ">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrders as $salesOrder)
                                <tr>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->order_number }}</td>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->order_id }}</td>
                                    <td class="py-2 px-4 ">{{ $salesOrder->partner->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4  text-center">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">معتمد</span>
                                    </td>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->issue_date }}</td>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->expected_delivery_date ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 ">
                                        <div class="flex justify-center space-x-1 space-x-reverse">
                                            <a href="{{ route('sales-orders.show', $salesOrder->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('invoices.create-from-sales-order', $salesOrder->id) }}" class="bg-green-100 text-white px-2 py-1 rounded hover:bg-green-600">
                                                <i class="fas fa-file-invoice"></i> إنشاء فاتورة
                                            </a>
                                            
                                            <a href="{{ route('sales-orders.print', $salesOrder->id) }}" class="bg-purple-500 text-white px-2 py-1 rounded hover:bg-purple-600" target="_blank">
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
