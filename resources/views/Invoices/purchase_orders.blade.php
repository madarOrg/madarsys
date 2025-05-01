<x-layout dir="rtl">
    <section class="relative mt-2 flex flex-col items-start">
        <x-title :title="'أوامر الشراء المعتمدة'"></x-title>
        
        <div class="w-full  p-4 rounded shadow">
            <div class="flex justify-between items-center mb-2">
                {{-- <h2 class="text-xl font-bold">قائمة أوامر الشراء المعتمدة</h2> --}}
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('purchase-orders.index') }}"  class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-white dark:text-gray-400 text-base font-semibold leading-7 flex items-center justify-center">

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
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="py-2 px-4 ">رقم أمر الشراء</th>
                                <th class="py-2 px-4 ">رقم الطلب</th>
                                <th class="py-2 px-4 ">المورد</th>
                                <th class="py-2 px-4 ">الحالة</th>
                                <th class="py-2 px-4 ">تاريخ الإصدار</th>
                                <th class="py-2 px-4 ">تاريخ التسليم المتوقع</th>
                                <th class="py-2 px-4 ">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrders as $purchaseOrder)
                            <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                <td class="py-2 px-4  text-center">{{ $purchaseOrder->order_number }}</td>
                                    <td class="py-2 px-4  text-center">{{ $purchaseOrder->order_id }}</td>
                                    <td class="py-2 px-4 ">{{ $purchaseOrder->partner->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4  text-center">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">معتمد</span>
                                    </td>
                                    <td class="py-2 px-4  text-center">{{ $purchaseOrder->issue_date }}</td>
                                    <td class="py-2 px-4  text-center">{{ $purchaseOrder->expected_delivery_date ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 ">
                                        <div class="flex justify-center space-x-1 space-x-reverse">
                                            <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="text-blue-500  px-2 py-1 rounded hover:text-blue-600">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('invoices.create-from-purchase-order', $purchaseOrder->id) }}" class="bg-green-200 text-gray-600 px-2 py-1 rounded hover:bg-green-200">
                                                <i class="fas fa-file-invoice"></i> إنشاء فاتورة
                                            </a>
                                            
                                            <a href="{{ route('purchase-orders.print', $purchaseOrder->id) }}" class="text-gray-600 px-2 py-1 rounded " target="_blank">
                                                <i class="fas fa-print fa-lg"></i>
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
