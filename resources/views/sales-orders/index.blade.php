<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إدارة أوامر الصرف'"></x-title>
        
        <div class="w-full mt-5 bg-white p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">قائمة أوامر الصرف</h2>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('orders.check-confirmed', ['type' => 'sell']) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-plus-circle ml-1"></i> إنشاء أمر صرف جديد
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
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b">رقم أمر الصرف</th>
                                <th class="py-2 px-4 border-b">رقم الطلب</th>
                                <th class="py-2 px-4 border-b">العميل</th>
                                <th class="py-2 px-4 border-b">الحالة</th>
                                <th class="py-2 px-4 border-b">تاريخ الإصدار</th>
                                <th class="py-2 px-4 border-b">تاريخ التسليم المتوقع</th>
                                <th class="py-2 px-4 border-b">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrders as $salesOrder)
                                <tr>
                                    <td class="py-2 px-4 border-b text-center">{{ $salesOrder->order_number }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $salesOrder->order_id }}</td>
                                    <td class="py-2 px-4 border-b">{{ $salesOrder->partner->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 border-b text-center">
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
                                    <td class="py-2 px-4 border-b text-center">{{ $salesOrder->issue_date }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $salesOrder->expected_delivery_date ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <div class="flex justify-center space-x-1 space-x-reverse">
                                            <a href="{{ route('sales-orders.show', $salesOrder->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(!in_array($salesOrder->status, ['completed', 'canceled']))
                                                <a href="{{ route('sales-orders.edit', $salesOrder->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($salesOrder->status == 'pending')
                                                    <form action="{{ route('sales-orders.approve', $salesOrder->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            
                                            <a href="{{ route('sales-orders.print', $salesOrder->id) }}" class="bg-purple-500 text-white px-2 py-1 rounded hover:bg-purple-600" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            
                                            @if(!$salesOrder->invoices()->exists())
                                                <form action="{{ route('sales-orders.destroy', $salesOrder->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف أمر الصرف هذا؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
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
