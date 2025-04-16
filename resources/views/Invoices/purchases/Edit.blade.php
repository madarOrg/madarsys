 <x-layout>

     <section class="mb-1 p-6  shadow-md rounded-lg">
         <form action="{{ route('invoices.update', ['type' => $invoice->type, 'invoice' => $invoice->id]) }}"
             method="POST">
             @csrf
             @method('PUT')
             <div class="">
                 <div class="pb-0">
                     <x-title :title="'تعديل فاتورة المشتريات'"></x-title>

                 </div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end w-full">
                     {{-- <div class="">
                        <label for="branch_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                        <select name="branch_id" id="branch_id"
                            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                            required>
                            <option value="">اختر الفرع</option>
                            @foreach ($Branchs as $branch)
                                <option value="{{ $branch->id }}" {{ $invoice->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                     <div class="m-2">
                         <label for="warehouse_id"
                             class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                         <select name="warehouse_id" id="warehouse_id" class="tom-select w-full" required>
                             <option value="" selected>اختر المستودع</option>
                             @foreach ($Warehouses as $Warehouse)
                                 <option value="{{ $Warehouse->id }}"
                                     {{ $invoice->warehouse_id == $Warehouse->id ? 'selected' : '' }}>
                                     {{ $Warehouse->name }}
                                 </option>
                             @endforeach
                         </select>
                     </div>


                     <div class="mb-2">
                         <label for="partner_id"
                             class="block text-sm font-medium text-gray-700 dark:text-gray-300">العميل</label>
                         <select name="partner_id" id="partner_id" class="tom-select w-full" required>
                             <option value="">اختر العميل</option>
                             @foreach ($partners as $partner)
                                 <option value="{{ $partner->id }}"
                                     {{ $invoice->partner_id == $partner->id ? 'selected' : '' }}>
                                     {{ $partner->name }}
                                 </option>
                             @endforeach
                         </select>

                         @error('partner_id')
                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                         @enderror
                     </div>
                     <div class="flex items-center gap-4">
                         <div class="w-1/3 mb-2">
                             <label for="invoice_Code" class="text-sm font-medium text-gray-600 dark:text-gray-400">رقم
                                 الفاتورة</label>
                             <input type="text" id="invoice_Code" name="invoice_Code"
                                 value="{{ $invoice->invoice_code }}"
                                 class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                 readonly />
                             @error('invoice_Code')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>
                         <div class="flex-1">
                             <x-file-input id="invoice_date" value="{{ $invoice->invoice_date }}" name="invoice_date"
                                 label="تاريخ الفاتورة" type="date" required="true" />
                             @error('invoice_date')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>
                     </div>
                     <div class="flex items-center gap-4">
                         <div class="w-1/3 mb-2">
                             <label for="payment_type_id"
                                 class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                             <select name="payment_type_id" id="payment_type_id"
                                 class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                 required>
                                 <option value="">اختر طريقة الدفع</option>
                                 @foreach ($paymentTypes as $paymentType)
                                     <option value="{{ $paymentType->id }}"
                                         {{ $invoice->payment_type_id == $paymentType->id ? 'selected' : '' }}>
                                         {{ $paymentType->name }}
                                     </option>
                                 @endforeach
                             </select>
                             @error('payment_type_id')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>

                         <div class="flex-1 mb-2">
                             <label for="currency_id"
                                 class="block text-sm font-medium text-gray-600 dark:text-gray-400">العملة</label>
                             <select name="currency_id" id="currency_id"
                                 class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                 required>
                                 @foreach ($currencies as $currency)
                                     <option value="{{ $currency->id }}"
                                         {{ $invoice->currency_id == $currency->id ? 'selected' : '' }}>
                                         {{ $currency->name }}
                                     </option>
                                 @endforeach
                             </select>
                             @error('currency_id')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>
                     </div>


                     <div class="flex items-center gap-4">
                         <div class="w-1/3">
                             <x-file-input id="exchange_rate" name="exchange_rate" label="سعر الصرف" type="number"
                                 step="0.0001" required="true" value="{{ $invoice->exchange_rate }}" />
                             @error('exchange_rate')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>
                         <div class="w-1/3">
                             <label for="discount_type"
                                 class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الخصم</label>
                             <select id="discount_type" name="discount_type"
                                 class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                 <option value="1"
                                     {{ old('discount_type', $invoice->discount_type) == '1' ? 'selected' : '' }}>مبلغ
                                 </option>
                                 <option value="2"
                                     {{ old('discount_type', $invoice->discount_type) == '2' ? 'selected' : '' }}>نسبة
                                 </option>
                             </select>
                         </div>
                         <div class="flex-1">
                             <label for="discount_value"
                                 class="block text-sm font-medium text-gray-700 dark:text-gray-300">القيمة</label>
                             <input type="text" id="discount_value" name="discount_value"
                                 value="{{ old('discount_value', $invoice->discount_type == '2' ? $invoice->discount_percentage : $invoice->discount_amount) }}"
                                 class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                 placeholder="قيمة الخصم">
                         </div>
                     </div>
                     <div class="">
                         <div id="check-number-container" class="hidden">
                             <label for="check_number"
                                 class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الشيك</label>
                             <input type="text" value="{{ $invoice->check_number }}" name="check_number"
                                 id="check_number"
                                 class="w-full bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">
                             @error('check_number')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>
                     </div>
                 </div>
                 <div class="overflow-x-auto">
                     <table id="invoice-items-table"
                         class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                         <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                             <tr>
                                 <th class="px-6 py-3">المنتج</th>
                                 <th class="px-6 py-3">الوحدة</th>
                                 <th class="px-6 py-3">الكمية</th>
                                 <th class="px-6 py-3">السعر</th>
                                 <th class="px-6 py-3">الإجمالي</th>
                                 <th class="px-6 py-3">تاريخ إنتاج المنتج </th>
                                 <th class="px-6 py-3">تاريخ إنتهاء المنتج </th>
                                 <th class="p-3">الإجراء</th>
                             </tr>
                         </thead>
                         <tbody id="transaction-items">
                            @foreach ($invoice->items as $index => $item)
                            <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->id ?? 0 }}">

                                <tr class="product-row border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                    <td class="px-0 py-4">
                                        <select name="items[{{ $index }}][product_id]" class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر منتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" @if ($product->id == $item->product_id) selected @endif>
                                                    {{ $product->id }} - {{ $product->name }} - SKU: {{ $product->sku }} - Barcode: {{ $product->barcode }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                        
                                    <td class="px-6 py-4">
                                        <select name="items[{{ $index }}][unit_id]" class="w-full units-select  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر وحدة</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" @if ($unit->id == $item->unit_id) selected @endif>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                        
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="w-full quantity-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                        
                                    <td>
                                        <input type="number" name="items[{{ $index }}][price]" value="{{ $item->price }}" class="w-full price-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                                    </td>
                        
                                    <td>
                                        <input type="number" name="items[{{ $index }}][total]" value="{{ $item->subtotal }}" class="w-full total-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                                    </td>
                        
                                    <td>
                                        <input type="date" name="items[{{ $index }}][production_date]" value="{{ $item->production_date }}" class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                        
                                    <td>
                                        <input type="date" name="items[{{ $index }}][expiration_date]" value="{{ $item->expiration_date }}" class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                        
                                    <td class="px-6 py-4">
                                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                     </table>
                 </div>
                 <div class="flex justify-start mt-4">
                     <x-button type="button" id="add-item"
                         class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">إضافة منتج</x-button>
                 </div>
                 <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">

                     <div class="col-span-1">
                         <x-file-input id="amount_before_discount" name="amount_before_discount"
                             value="{{ $invoice->amount_before_discount }}" readonly label="الإجمالي قبل الخصم" />
                         @error('amount_before_discount')
                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                     <div class="col-span-1">
                         <x-file-input id="discount_amount" name="discount_amount"
                             value="{{ $invoice->discount_amount }}" readonly label="مبلغ الخصم" />
                         @error('discount_amount')
                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                     <div class="col-span-1">
                         <x-file-input id="total_amount" name="total_amount" value="{{ $invoice->total_amount }}"
                             readonly label="المجموع الكلي بعد الخصم" />
                         @error('total_amount')
                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                 </div>
             </div>
             <div class="flex justify-start mt-4">
                 <x-button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">تعديل
                     الفاتورة</x-button>
             </div>
         </form>
     </section>

     <script>
         let products = @json($products);
         let units = @json($units);

         document.getElementById('add-item').addEventListener('click', function () {
    let index = document.querySelectorAll('#invoice-items-table tbody tr').length;

    let productOptions = products.map(product =>
        `<option value="${product.id}" data-price="${product.selling_price}" data-unit-id="${product.unit_id}">
            ${product.id} - ${product.name} - SKU: ${product.sku} - Barcode: ${product.barcode}
        </option>`
    ).join('');

    let unitOptions = units.map(unit =>
        `<option value="${unit.id}">${unit.name}</option>`
    ).join('');

    let newRow = `
        <tr class="product-row border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
            <input type="hidden" name="items[${index}][item_id]" value="0"> <!-- new item -->

            <td class="px-0 py-4">
                <select name="items[${index}][product_id]"
                    class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="">اختر منتج</option>
                    ${productOptions}
                </select>
            </td>

            <td class="px-6 py-4">
                <select name="items[${index}][unit_id]"
                    class="w-full units-select  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="">اختر وحدة</option>
                    ${unitOptions}
                </select>
            </td>

            <td class="">
                <input type="number" name="items[${index}][quantity]" value="1"
                    class="w-full quantity-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
            </td>

            <td class="">
                <input type="number" name="items[${index}][price]" value=""
                    class="w-full price-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                    min="0" step="0.01" />
            </td>

            <td class="">
                <input type="number" name="items[${index}][total]" value=""
                    class="w-full total-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                    min="0" step="0.01" />
            </td>

            <td class="">
                <input type="date" name="items[${index}][production_date]"
                    class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
            </td>

            <td class="">
                <input type="date" name="items[${index}][expiration_date]"
                    class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
            </td>

            <td class="px-6 py-4">
                <button type="button" class="remove-item text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `;

    document.querySelector('#invoice-items-table tbody').insertAdjacentHTML('beforeend', newRow);

    // تفعيل tom-select لل select الجديد
    new TomSelect('.product-select:last');

    updateTotalPrice(); // تحديث السعر الإجمالي بعد الإضافة
});


         document.querySelector('#invoice-items-table').addEventListener('change', function(e) {
             if (e.target.classList.contains('product-select')) {
                 let selectedOption = e.target.options[e.target.selectedIndex];
                 let priceInput = e.target.closest('tr').querySelector('.price-input');
                 let unitselect = e.target.closest('tr').querySelector('.unit-select');

                 let unitId = selectedOption.getAttribute('data-unit-id');

                 if (unitselect && unitId) {
                     unitselect.value = unitId;
                 }

                 priceInput.value = selectedOption.getAttribute('data-price') || 0;
                 updateTotalPrice();
             }
         });

         document.querySelector('#invoice-items-table').addEventListener('input', function(e) {
             if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
                 updateTotalPrice();
             }
         });

         document.querySelector('#invoice-items-table').addEventListener('click', function(e) {
             if (e.target.classList.contains('remove-item')) {
                 e.target.closest('tr').remove();
                 updateTotalPrice();
             }

         });
         document.getElementById('exchange_rate').addEventListener('input', updateTotalPrice);
         document.getElementById('discount_value').addEventListener('input', updateTotalPrice);
         document.getElementById('discount_type').addEventListener('change', updateTotalPrice);

         document.getElementById('payment_type_id').addEventListener('change', function() {
             const selectedPaymentType = this.value;
             const checkNumberContainer = document.getElementById('check-number-container');
             if (selectedPaymentType == '4') {
                 checkNumberContainer.classList.remove('hidden');
             } else {
                 checkNumberContainer.classList.add('hidden');
             }
         });

         function updateTotalPrice() {
             let totalPrice = 0;

             // Calculate total price from quantity and price
             document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
                 let quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                 let price = parseFloat(row.querySelector('.price-input').value) || 0;
                 totalPrice += (quantity * price);
             });



             // Get exchange rate value
             let exchangeRate = parseFloat(document.getElementById('exchange_rate').value) || 1;
             let convertedTotalPrice = totalPrice * exchangeRate;

             // Store the amount before discount
             document.getElementById('amount_before_discount').value = convertedTotalPrice.toFixed(2);

             // Get discount value and type
             let discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
             let discountType = document.getElementById('discount_type').value;
             let discountAmount = 0;

             if (discountType === '2') { // Percentage discount
                 discountAmount = convertedTotalPrice * (discountValue / 100);
                 convertedTotalPrice = convertedTotalPrice * (1 - discountValue / 100);
             } else { // Fixed amount discount
                 discountAmount = discountValue;
                 convertedTotalPrice -= discountValue;
             }

             // Update UI fields
             document.getElementById('discount_amount').value = discountAmount.toFixed(2);
             document.getElementById('total_amount').value = convertedTotalPrice.toFixed(2);
         }

         document.addEventListener('DOMContentLoaded', function() {
             const paymentTypeSelect = document.getElementById('payment_type_id');
             const checkNumberContainer = document.getElementById('check-number-container');

             function handlePaymentTypeChange() {
                 const selectedPaymentType = paymentTypeSelect.value;
                 if (selectedPaymentType == '4') {
                     checkNumberContainer.classList.remove('hidden');
                 } else {
                     checkNumberContainer.classList.add('hidden');
                 }
             }

             handlePaymentTypeChange();
             paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);

             function updateProductPrice() {
                 document.querySelectorAll('.product-select').forEach(select => {
                     select.addEventListener('change', function() {
                         let selectedOption = this.options[this.selectedIndex];
                         let priceInput = this.closest('tr').querySelector('.price-input');
                         let sellingPrice = selectedOption.getAttribute('data-price');

                         if (sellingPrice) {
                             priceInput.value = sellingPrice;
                         }
                     });
                 });
             }

             updateProductPrice(); // Initialize the function

             updateTotalPrice();
         });
     </script>
 </x-layout>
