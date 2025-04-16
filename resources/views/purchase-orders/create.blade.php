<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إنشاء أمر شراء جديد'"></x-title>
        
        <div class="w-full mt-5 bg-white p-6 rounded-lg shadow-md">
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">معلومات الطلب الأصلي</h2>
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-md">
                    <div>
                        <p class="font-semibold">رقم الطلب: <span class="font-normal">{{ $order->id }}</span></p>
                        <p class="font-semibold">نوع الطلب: <span class="font-normal">{{ $order->type == 'buy' ? 'شراء' : 'بيع' }}</span></p>
                        <p class="font-semibold">حالة الطلب: <span class="font-normal">{{ $order->status }}</span></p>
                    </div>
                    <div>
                        <p class="font-semibold">المورد: <span class="font-normal">{{ $order->partner->name ?? 'غير محدد' }}</span></p>
                        <p class="font-semibold">الفرع: <span class="font-normal">{{ $order->branch->name ?? 'غير محدد' }}</span></p>
                        <p class="font-semibold">تاريخ الإنشاء: <span class="font-normal">{{ $order->created_at->format('Y-m-d') }}</span></p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('purchase-orders.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="partner_id" class="block text-gray-700 font-semibold mb-2">المورد</label>
                        <select name="partner_id" id="partner_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">اختر المورد</option>
                            @if($order->partner)
                                <option value="{{ $order->partner->id }}" selected>{{ $order->partner->name }}</option>
                            @endif
                        </select>
                    </div>
                    
                    <div>
                        <label for="issue_date" class="block text-gray-700 font-semibold mb-2">تاريخ الإصدار</label>
                        <input type="date" name="issue_date" id="issue_date" value="{{ date('Y-m-d') }}" class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="expected_delivery_date" class="block text-gray-700 font-semibold mb-2">تاريخ التسليم المتوقع</label>
                        <input type="date" name="expected_delivery_date" id="expected_delivery_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" class="w-full p-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div>
                    <label for="notes" class="block text-gray-700 font-semibold mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full p-2 border border-gray-300 rounded-md"></textarea>
                </div>
                
                <div class="mt-6">
                    <h2 class="text-xl font-bold mb-4">تفاصيل الطلب</h2>
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b">المنتج</th>
                                <th class="py-2 px-4 border-b">الكمية</th>
                                <th class="py-2 px-4 border-b">السعر</th>
                                <th class="py-2 px-4 border-b">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->order_details as $detail)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $detail->product->name ?? 'غير محدد' }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $detail->quantity }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $detail->price }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $detail->quantity * $detail->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="py-2 px-4 border-b text-left font-bold">الإجمالي</td>
                                <td class="py-2 px-4 border-b text-center font-bold">
                                    {{ $order->order_details->sum(function($detail) { return $detail->quantity * $detail->price; }) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-8 flex justify-center">
                    <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-10 rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-75 transition duration-300 ease-in-out text-lg">
                        <i class="fas fa-save mr-2"></i> حفظ أمر الشراء
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
