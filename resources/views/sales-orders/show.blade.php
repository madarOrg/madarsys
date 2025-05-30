<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'تفاصيل أمر الصرف'"></x-title>
        
        <div class="w-full mt-5  p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">أمر صرف رقم: {{ $salesOrder->order_number }}</h2>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('sales-orders.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        <i class="fas fa-arrow-right ml-1"></i> العودة
                    </a>
                    
                    @if($salesOrder->status === 'pending')
                        <form action="{{ route('sales-orders.approve', $salesOrder->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-gray-600 px-4 py-2 rounded-md hover:bg-green-700">
                                <i class="fas fa-check ml-1"></i> اعتماد
                            </button>
                        </form>
                    @endif
                    
                    @if($salesOrder->status === 'approved')
                        <a href="{{ route('invoices.create-from-sales-order', $salesOrder->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-file-invoice ml-1"></i> إنشاء فاتورة
                        </a>
                    @endif
                    
                    <a href="{{ route('sales-orders.print', $salesOrder->id) }}" class="bg-green-200 text-gray-600 px-4 py-2 rounded-md hover:bg-green-700" target="_blank">
                        <i class="fas fa-print ml-1"></i> طباعة
                    </a>
                    
                    {{-- <a href="{{ route('sales-orders.edit', $salesOrder->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                        <i class="fas fa-edit ml-1"></i> تعديل
                    </a> --}}
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6 mb-6 rounded-md border border-gray-300">
                <div class=" p-4 rounded-md ">
                    <h3 class="text-lg font-semibold mb-3">معلومات أمر الصرف</h3>
                    <div class="grid grid-cols-2 gap-4 ">
                        <div>
                            <p class="font-semibold">رقم أمر الصرف: <span class="font-normal">{{ $salesOrder->order_number }}</span></p>
                            <p class="font-semibold">رقم الطلب الأصلي: <span class="font-normal">{{ $salesOrder->order_id }}</span></p>
                            <p class="font-semibold">تاريخ الإصدار: <span class="font-normal">{{ $salesOrder->issue_date }}</span></p>
                        </div>
                        <div>
                            <p class="font-semibold">الحالة: 
                                <span class="font-normal">
                                    @if($salesOrder->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">قيد الانتظار</span>
                                    @elseif($salesOrder->status === 'approved')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">معتمد</span>
                                    @elseif($salesOrder->status === 'completed')
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">مكتمل</span>
                                    @elseif($salesOrder->status === 'canceled')
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">ملغي</span>
                                    @endif
                                </span>
                            </p>
                            <p class="font-semibold">تاريخ التسليم المتوقع: <span class="font-normal">{{ $salesOrder->expected_delivery_date ?? 'غير محدد' }}</span></p>
                            <p class="font-semibold">تمت الطباعة: <span class="font-normal">{{ $salesOrder->is_printed ? 'نعم' : 'لا' }}</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="b p-4 rounded-md">
                    <h3 class="text-lg font-semibold mb-3">معلومات العميل</h3>
                    <div>
                        <p class="font-semibold">اسم العميل: <span class="font-normal">{{ $salesOrder->partner->name ?? 'غير محدد' }}</span></p>
                        <p class="font-semibold">رقم الهاتف: <span class="font-normal">{{ $salesOrder->partner->phone ?? 'غير محدد' }}</span></p>
                        <p class="font-semibold">البريد الإلكتروني: <span class="font-normal">{{ $salesOrder->partner->email ?? 'غير محدد' }}</span></p>
                        <p class="font-semibold">العنوان: <span class="font-normal">{{ $salesOrder->partner->address ?? 'غير محدد' }}</span></p>
                    </div>
                </div>
            </div>
            
            @if($salesOrder->notes)
                <div class="bg-gray-50 p-4 rounded-md mb-6">
                    <h3 class="text-lg font-semibold mb-2">ملاحظات</h3>
                    <p>{{ $salesOrder->notes }}</p>
                </div>
            @endif
            
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3">تفاصيل الطلب</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="py-2 px-4 ">#</th>
                                <th class="py-2 px-4 ">المنتج</th>
                                <th class="py-2 px-4 ">الوحدة</th>
                                <th class="py-2 px-4 ">الكمية</th>
                                <th class="py-2 px-4 ">السعر</th>
                                <th class="py-2 px-4 ">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($salesOrder->order->order_details as $index => $detail)
                                @php $subtotal = $detail->quantity * $detail->price; $total += $subtotal; @endphp
                                <tr  class="item-row bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <td class="py-2 px-4  text-center">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4 ">{{ $detail->product->name ?? 'غير محدد' }}-{{ $detail->product->barcode ?? 'غير محدد' }}-{{ $detail->product->sku ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4  text-center">{{ $detail->unit->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4  text-center">{{ $detail->quantity }}</td>
                                    <td class="py-2 px-4  text-center">{{ number_format($detail->price, 2) }}</td>
                                    <td class="py-2 px-4  text-center">{{ number_format($subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="5" class="py-2 px-4 border-b text-left font-bold">الإجمالي</td>
                                <td class="py-2 px-4 border-b text-center font-bold">{{ number_format($total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            @if($salesOrder->status === 'completed' && $invoice)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-3">معلومات الفاتورة</h3>
                    <div class="bg-blue-50 p-4 rounded-md">
                        <p class="font-semibold">رقم الفاتورة: <span class="font-normal">{{ $invoice->invoice_code }}</span></p>
                        <p class="font-semibold">تاريخ الفاتورة: <span class="font-normal">{{ $invoice->invoice_date }}</span></p>
                        <p class="font-semibold">المبلغ الإجمالي: <span class="font-normal">{{ number_format($invoice->total_amount, 2) }}</span></p>
                        <div class="mt-2">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:underline">
                                <i class="fas fa-external-link-alt mr-1"></i> عرض تفاصيل الفاتورة
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layout>
