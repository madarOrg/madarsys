<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إنشاء فاتورة بيع من أمر صرف'"></x-title>
        
        <div class="w-full mt-5 bg-white p-6 rounded-lg shadow-md">
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">معلومات أمر الصرف</h2>
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-md">
                    <div>
                        <p class="font-semibold">رقم أمر الصرف: <span class="font-normal">{{ $salesOrder->order_number }}</span></p>
                        <p class="font-semibold">رقم الطلب الأصلي: <span class="font-normal">{{ $salesOrder->order_id }}</span></p>
                        <p class="font-semibold">العميل: <span class="font-normal">{{ $salesOrder->partner->name ?? 'غير محدد' }}</span></p>
                    </div>
                    <div>
                        <p class="font-semibold">تاريخ الإصدار: <span class="font-normal">{{ $salesOrder->issue_date }}</span></p>
                        <p class="font-semibold">تاريخ التسليم المتوقع: <span class="font-normal">{{ $salesOrder->expected_delivery_date ?? 'غير محدد' }}</span></p>
                        <p class="font-semibold">الحالة: <span class="font-normal">معتمد</span></p>
                    </div>
                </div>
            </div>
            
            <form action="/invoices/store-from-sales-order/{{ $salesOrder->id }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label for="partner_id" class="block text-gray-700 font-semibold mb-2">العميل</label>
                        <select name="partner_id" id="partner_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">اختر العميل</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}" {{ $salesOrder->partner_id == $partner->id ? 'selected' : '' }}>
                                    {{ $partner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="invoice_date" class="block text-gray-700 font-semibold mb-2">تاريخ الفاتورة</label>
                        <input type="date" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}" class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>
                    
                    <div>
                        <label for="payment_type_id" class="block text-gray-700 font-semibold mb-2">طريقة الدفع</label>
                        <select name="payment_type_id" id="payment_type_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">اختر طريقة الدفع</option>
                            @foreach($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label for="branch_id" class="block text-gray-700 font-semibold mb-2">الفرع</label>
                        <select name="branch_id" id="branch_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">اختر الفرع</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $salesOrder->order->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="warehouse_id" class="block text-gray-700 font-semibold mb-2">المستودع</label>
                        <select name="warehouse_id" id="warehouse_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">اختر المستودع</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="currency_id" class="block text-gray-700 font-semibold mb-2">العملة</label>
                        <select name="currency_id" id="currency_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">اختر العملة</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label for="exchange_rate" class="block text-gray-700 font-semibold mb-2">سعر الصرف</label>
                        <input type="number" name="exchange_rate" id="exchange_rate" value="1" step="0.01" min="0.01" class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>
                    
                    <div>
                        <label for="discount_type" class="block text-gray-700 font-semibold mb-2">نوع الخصم</label>
                        <select name="discount_type" id="discount_type" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="1">مبلغ ثابت</option>
                            <option value="2">نسبة مئوية</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="discount_value" class="block text-gray-700 font-semibold mb-2">قيمة الخصم</label>
                        <input type="number" name="discount_value" id="discount_value" value="0" step="0.01" min="0" class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h2 class="text-xl font-bold mb-4">تفاصيل الفاتورة</h2>
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b">المنتج</th>
                                <th class="py-2 px-4 border-b">الوحدة</th>
                                <th class="py-2 px-4 border-b">الكمية</th>
                                <th class="py-2 px-4 border-b">السعر</th>
                                
                                <th class="py-2 px-4 border-b">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-items">
                            @foreach($salesOrder->order->order_details as $index => $detail)
                                <tr>
                                    <td class="py-2 px-4 border-b">
                                        <select name="items[{{ $index }}][product_id]" class="w-full p-2 border border-gray-300 rounded-md product-select" required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-unit="{{ $product->unit_id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <select name="items[{{ $index }}][unit_id]" class="w-full p-2 border border-gray-300 rounded-md unit-select" required>
                                            <option value="">اختر الوحدة</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $detail->quantity }}" min="1" step="1" class="w-full p-2 border border-gray-300 rounded-md quantity-input" required>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <input type="number" name="items[{{ $index }}][price]" value="{{ $detail->price }}" min="0.01" step="0.01" class="w-full p-2 border border-gray-300 rounded-md price-input" required>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <input type="text" readonly class="w-full p-2 bg-gray-100 border border-gray-300 rounded-md subtotal-display">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="4" class="py-2 px-4 border-b text-left font-bold">الإجمالي</td>
                                <td class="py-2 px-4 border-b">
                                    <input type="text" id="total-amount" readonly class="w-full p-2 bg-gray-100 border border-gray-300 rounded-md">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-8 flex justify-center">
                    <button type="submit" class="bg-green-600 text-white font-bold py-3 px-10 rounded-lg shadow-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-75 transition duration-300 ease-in-out text-lg">
                        <i class="fas fa-save mr-2"></i> حفظ الفاتورة وإنشاء حركة مخزنية
                    </button>
                </div>
            </form>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث الوحدة عند اختيار المنتج
            const productSelects = document.querySelectorAll('.product-select');
            productSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    const row = this.closest('tr');
                    const unitSelect = row.querySelector('.unit-select');
                    const priceInput = row.querySelector('.price-input');
                    
                    if (option.value) {
                        const unitId = option.getAttribute('data-unit');
                        const price = option.getAttribute('data-price');
                        
                        if (unitId) {
                            for (let i = 0; i < unitSelect.options.length; i++) {
                                if (unitSelect.options[i].value == unitId) {
                                    unitSelect.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                        
                        if (price) {
                            priceInput.value = price;
                        }
                    }
                    
                    updateSubtotal(row);
                    updateTotal();
                });
            });
            
            // تحديث الإجمالي عند تغيير الكمية أو السعر
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const priceInputs = document.querySelectorAll('.price-input');
            
            quantityInputs.forEach(input => {
                input.addEventListener('input', function() {
                    updateSubtotal(this.closest('tr'));
                    updateTotal();
                });
            });
            
            priceInputs.forEach(input => {
                input.addEventListener('input', function() {
                    updateSubtotal(this.closest('tr'));
                    updateTotal();
                });
            });
            
            // تحديث إجمالي الصف
            function updateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const subtotal = quantity * price;
                row.querySelector('.subtotal-display').value = subtotal.toFixed(2);
            }
            
            // تحديث الإجمالي الكلي
            function updateTotal() {
                const subtotals = document.querySelectorAll('.subtotal-display');
                let total = 0;
                
                subtotals.forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                
                document.getElementById('total-amount').value = total.toFixed(2);
            }
            
            // تحديث الإجماليات عند تحميل الصفحة
            document.querySelectorAll('tr').forEach(row => {
                if (row.querySelector('.quantity-input')) {
                    updateSubtotal(row);
                }
            });
            updateTotal();
        });
    </script>
</x-layout>
