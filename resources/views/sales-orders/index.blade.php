<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إدارة أوامر الصرف'"></x-title>
        
        <div class="w-full mt-5  p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('orders.check-confirmed', ['type' => 'sell']) }}"
                        class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-white dark:text-gray-400 text-base font-semibold leading-7 flex items-center justify-center">
                         <i class="fas fa-plus-circle ml-1"></i> 
                         إنشاء أمر صرف جديد
                     </a>
                     <a href="{{ route('invoices.sales-orders') }}"
                        class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-white dark:text-gray-400 text-base font-semibold leading-7 flex items-center justify-center">
                        
                      أوامر الصرف المعتمدة
                     </a>
                     
                </div>
            </div>
            
            @if($salesOrders->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    <p class="font-bold">لا توجد أوامر صرف حالياً.</p>
                    <p class="mt-2">يمكنك إنشاء أمر صرف جديد من خلال الضغط على زر "إنشاء أمر صرف جديد".</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
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
                                <tr
                                class="bg-gray-200  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                <td class="py-2 px-4  text-center">{{ $salesOrder->order_number }}</td>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->order_id }}</td>
                                    <td class="py-2 px-4 ">{{ $salesOrder->partner->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4  text-center">
                                        @if($salesOrder->status == 'pending')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">معلق</span>
                                        @elseif($salesOrder->status == 'approved')
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">معتمد</span>
                                        @elseif($salesOrder->status == 'completed')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">مكتمل</span>
                                        @elseif($salesOrder->status == 'canceled')
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">ملغي</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->issue_date }}</td>
                                    <td class="py-2 px-4  text-center">{{ $salesOrder->expected_delivery_date ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 ">
                                        <div class="flex justify-center space-x-1 space-x-reverse">
                                            <a href="{{ route('sales-orders.show', $salesOrder->id) }}" class="text-blue-500  px-2 py-1 rounded hover:text-blue-600">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(!in_array($salesOrder->status, ['completed', 'canceled']))
                                                <a href="{{ route('sales-orders.edit', $salesOrder->id) }}" class=" text-yellow-500 px-2 py-1 rounded hover:text-yellow-600">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($salesOrder->status == 'pending')
                                                    <form action="{{ route('sales-orders.approve', $salesOrder->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-500  px-2 py-1 rounded hover:text-green-600">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            
                                            <a href="{{ route('sales-orders.print', $salesOrder->id) }}" class="text-purple-500  px-2 py-1 rounded hover:text-purple-600" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            
                                            @if(!$salesOrder->invoices()->exists())
                                                <form action="{{ route('sales-orders.destroy', $salesOrder->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف أمر الصرف هذا؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class=" text-red-500 px-2 py-1 rounded hover:text-red-600">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
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
