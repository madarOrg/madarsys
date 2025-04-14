<x-layout dir="rtl">
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div class="flex justify-between">
            <h2 class="text-2xl font-semibold leading-tight">إنشاء فاتورة شراء من طلب رقم: {{ $order->id }}</h2>
            <a href="{{ route('invoices.confirmed-orders') }}" class="px-4 py-2 bg-gray-500 text-white rounded">العودة</a>
        </div>
        
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-4">
            <form action="/invoices/store-from-order/{{ $order->id }}" method="POST">
                @csrf
                
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="partner_id">
                            المورد
                        </label>
                        <select name="partner_id" id="partner_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="{{ $order->partner_id }}" selected>{{ $order->partner->name }}</option>
                        </select>
                        @error('partner_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="invoice_date">
                            تاريخ الفاتورة
                        </label>
                        <input type="date" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        @error('invoice_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="payment_type_id">
                            طريقة الدفع
                        </label>
                        <select name="payment_type_id" id="payment_type_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="{{ $order->payment_type_id }}" selected>{{ $order->paymentType->name }}</option>
                            @foreach($paymentTypes as $paymentType)
                                @if($paymentType->id != $order->payment_type_id)
                                <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('payment_type_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="branch_id">
                            الفرع
                        </label>
                        <select name="branch_id" id="branch_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="{{ $order->branch_id }}" selected>{{ $order->branch->name }}</option>
                            @foreach($branches as $branch)
                                @if($branch->id != $order->branch_id)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('branch_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="warehouse_id">
                            المستودع
                        </label>
                        <select name="warehouse_id" id="warehouse_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="check_number">
                            رقم الشيك (اختياري)
                        </label>
                        <input type="text" name="check_number" id="check_number" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        @error('check_number')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="currency_id">
                            العملة
                        </label>
                        <select name="currency_id" id="currency_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                            @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="exchange_rate">
                            سعر الصرف
                        </label>
                        <input type="number" step="0.01" name="exchange_rate" id="exchange_rate" value="1" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        @error('exchange_rate')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="department_id">
                            القسم (اختياري)
                        </label>
                        <input type="text" name="department_id" id="department_id" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        @error('department_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="discount_type">
                            نوع الخصم
                        </label>
                        <select name="discount_type" id="discount_type" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="1">مبلغ ثابت</option>
                            <option value="2">نسبة مئوية</option>
                        </select>
                        @error('discount_type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="discount_value">
                            قيمة الخصم
                        </label>
                        <input type="number" step="0.01" name="discount_value" id="discount_value" value="0" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        @error('discount_value')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-4">عناصر الفاتورة</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border">المنتج</th>
                                    <th class="px-4 py-2 border">الكمية</th>
                                    <th class="px-4 py-2 border">السعر</th>
                                    <th class="px-4 py-2 border">الوحدة</th>
                                    <th class="px-4 py-2 border">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody id="items-container">
                                @foreach($order->order_details as $index => $detail)
                                <tr class="item-row">
                                    <td class="px-4 py-2 border">
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $detail->product_id }}">
                                        <span>{{ $detail->product->name }}</span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $detail->quantity }}" min="1" class="quantity-input w-full p-2 border rounded" required>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <input type="number" step="0.01" name="items[{{ $index }}][price]" value="{{ $detail->price }}" min="0" class="price-input w-full p-2 border rounded" required>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <select name="items[{{ $index }}][unit_id]" class="w-full p-2 border rounded" required>
                                            @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $detail->product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="subtotal">{{ $detail->quantity * $detail->price }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-right font-bold">الإجمالي:</td>
                                    <td class="px-4 py-2 border">
                                        <span id="total-amount">0</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        حفظ الفاتورة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // حساب المجموع الفرعي عند تغيير الكمية أو السعر
        const calculateSubtotal = (row) => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = quantity * price;
            row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
            calculateTotal();
        };
        
        // حساب المجموع الكلي
        const calculateTotal = () => {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(element => {
                total += parseFloat(element.textContent) || 0;
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        };
        
        // إضافة مستمعي الأحداث لجميع الصفوف الحالية
        document.querySelectorAll('.item-row').forEach(row => {
            row.querySelector('.quantity-input').addEventListener('input', () => calculateSubtotal(row));
            row.querySelector('.price-input').addEventListener('input', () => calculateSubtotal(row));
            calculateSubtotal(row);
        });
        
        // حساب المجموع الكلي عند تحميل الصفحة
        calculateTotal();
    });
</script>

</x-layout>
