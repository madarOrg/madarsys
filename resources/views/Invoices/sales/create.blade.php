<x-layout>
    <form method="GET" action="{{ route('invoices.create',['type' => 'sale'])}}">
        <select name="warehouse_id" onchange="this.form.submit()">
            <option value="">اختر المستودع</option>
            @foreach($Warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
       
      </div>
      </form>
    
    <section class="mb-1 p-6  shadow-md rounded-lg">
      
        <form action="{{ route('invoices.store', ['type' => 'sale']) }}" method="POST">
            @csrf
            <div class="  dark:bg-gray-900 mb-24">
                
                <div class="">
                    <x-title :title="'إنشاء فاتورة مبيعات جديدة'"></x-title>
                </div>
                <div class="">
                    <x-file-input id="invoice_Code" value="{{ request('invoice_code') ?? '' }}" name="invoice_Code"
                       label=" "  hidden />
                    @error('invoice_Code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end w-full">
                
                    {{-- <div class="mb-2">
                        <label for="warehouse_id"
                            class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                        <select name="warehouse_id" id="warehouse_id" class="tom-select w-full " required>
                            <option value="" selected>اختر المستودع</option>
                            @foreach ($Warehouses as $Warehouse)
                                <option value="{{ $Warehouse->id }}">{{ $Warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <!-- partner Selection -->
                    <div class="mb-2">
                        <label for="partner_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">العميل</label>
                        <select name="partner_id" id="partner_id" class="tom-select w-full" required>
                            <option value="">اختر العميل</option>
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                        @error('partner_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Invoice Date -->
                  
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <x-file-input id="invoice_date" name="invoice_date" label="تاريخ الفاتورة" type="date"
                                required="true" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" />
                            @error('invoice_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Payment Method -->
                        <div class="w-1/3 mb-2">
                            <label for="payment_type_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                                <select name="payment_type_id" id="payment_type_id"
                                class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                required>
                                
                                @foreach ($paymentTypes as $paymentType)
                                    <option value="{{ $paymentType->id }}" 
                                        {{ (old('payment_type_id', $selectedPaymentTypeId) == $paymentType->id) ? 'selected' : '' }}>
                                        {{ $paymentType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                        <label for="currency_id"
                            class="block text-sm font-medium text-gray-600 dark:text-gray-400">العملة</label>
                        <select name="currency_id" id="currency_id"
                            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                            required>
                            @foreach ($currencies as $index => $currency)
                                <option value="{{ $currency->id }}" {{ $index == 0 ? 'selected' : '' }}>
                                    {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="w-1/3 mt-2">
                        <x-file-input id="exchange_rate" name="exchange_rate" label="سعر الصرف" type="number"
                            step="0.0001" required="true" value="1" />
                        @error('exchange_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                    <div class="flex items-center gap-4">
                        <div class="w-1/3 mb-0">
                            <label for="discount_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الخصم</label>
                            <select id="discount_type" name="discount_type"
                                class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                <option value="1">مبلغ</option>
                                <option value="2">نسبة</option>
                            </select>
                        </div>

                        <div class="flex-1  mb-0">
                            <label for="discount_value"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">القيمة</label>
                            <input type="text" id="discount_value" name="discount_value" value="0"
                                class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                placeholder="قيمة الخصم">
                        </div>
                    </div>
                    <div class="col-span-1">
                        <div id="check-number-container" class="hidden">
                            <label for="check_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الشيك</label>
                            <input type="text" value="0" name="check_number" id="check_number"
                                class="w-full bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">
                            @error('check_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-6">تفاصيل الفاتورة</h4> --}}
                <div class="overflow-x-auto">
                        <table id="invoice-items-table" class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
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
                            @foreach (old('products', []) as $index => $productId)
                                <tr
                                    class="product-row border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                    <td class="px-0 py-4">
                                        <select name="items[{{ $index }}][product_id]"
                                            class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر منتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if ($product->id == $productId) selected @endif>
                                                    {{ $product->id }} - {{ $product->name }} - SKU:
                                                    {{ $product->sku }} - Barcode: {{ $product->barcode }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="px-6 py-4">
                                        <select name="items[{{ $index }}][unit_id]"
                                            class="w-full units-select tom-select border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر وحدة</option>
                                            <!-- أضف هنا خيارات الوحدة بناءً على بياناتك -->
                                        </select>
                                    </td>

                                    <td class="">
                                        <input type="number" name="items[{{ $index }}][quantity]"
                                            class="w-full quantity-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>

                                    <td class="">
                                        <input type="number" name="items[{{ $index }}][price]"
                                            class="w-full price-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                            min="0" step="0.01" />
                                    </td>

                                    <td class="">
                                        <input type="number" name="items[{{ $index }}][total]"
                                            class="w-full total-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                            min="0" step="0.01" />
                                    </td>

                                    <td class="">
                                        <input type="date" name="items[{{ $index }}][production_date]"
                                            class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>

                                    <td class="">
                                        <input type="date" name="items[{{ $index }}][expiration_date]"
                                            class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>

                                    <td class="px-6 py-4">
                                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800"
                                            onclick="removeProductRow(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <!-- زر إضافة صف جديد -->
                    <div class="flex justify-start mt-4">
                        <x-button-secondary type="button" onclick="addProductRow()">+ إضافة منتج</x-button-secondary>
                    </div>
                    <div class=" mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                        <div class="col-span-1">
                            <x-file-input id="amount_before_discount" name="amount_before_discount" value="0"
                                readonly label="الاجمالي قبل الخصم " />
                            @error('amount_before_discount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="discount_amount" name="discount_amount" value="0" readonly
                                label="مبلغ الخصم" />
                            @error('discount_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror


                        </div>
                        <!-- Total Price -->
                        <div class="col-span-1">
                            <x-file-input id="total_amount" name="total_amount" value="0" readonly
                                label="المجموع الكلي بعد الخصم" />
                            @error('total_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="flex justify-start mt-4">
                    <x-button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ
                        الفاتورة</x-button>
                </div>
        </form>
    </section>

    <script>
            let index = 0; // Initialize index to 0

        function addProductRow() {
            const tableBody = document.getElementById('transaction-items');
            const newRow = `
               <tr class="product-row border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
    <td class="px-0 py-2">
        <select name="items[${index}][product_id]" class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
            <option value="">اختر منتج</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}">
                   {{ $product->id }} - {{ $product->name }} - SKU: {{ $product->sku }} - Barcode: {{ $product->barcode }}
                </option>
            @endforeach
        </select>
    </td>
    <td class="px-6 py-4">
        <select name="items[${index}][unit_id]" class="w-full units-select tom-select border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
            <option value="">اختر وحدة</option>
        </select>
    </td>
    <td class="">
        <input type="number" name="items[${index}][quantity]" class="w-full quantity-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
    </td>
    <td class="">
        <input type="number" name="items[${index}][price]" class="w-full price-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
    </td>
    <td class="">
        <input type="number" name="items[${index}][total]" class="w-full total-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
    </td>
    <td class="">
        <input type="date" name="items[${index}][production_date]" class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
    </td>
    <td class="">
        <input type="date" name="items[${index}][expiration_date]" class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
    </td>
    <td class="px-6 py-4">
        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>
`;
            tableBody.insertAdjacentHTML('beforeend', newRow);

            const newSelect = tableBody.querySelector('.product-row:last-child .product-select');
            if (newSelect) {
                new TomSelect(newSelect, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "اختر منتج",
                });
            }

            const quantityInput = tableBody.querySelector('.product-row:last-child .quantity-input');
            const unitPriceInput = tableBody.querySelector('.product-row:last-child .price-input');
            // const discountInput = tableBody.querySelector('.product-row:last-child .item-discount-input');
            const totalInput = tableBody.querySelector('.product-row:last-child .total-input');

            [quantityInput, unitPriceInput].forEach(input => {
                input.addEventListener('input', calculateTotal);
            });

            function calculateTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                // const discount = parseFloat(discountInput.value) || 0;
                const total = (quantity * unitPrice);
                totalInput.value = total.toFixed(2);
                updateTotalPrice(); // لتحديث الإجمالي الكلي بعد كل تغيير
            }

            // localStorage.removeItem('currentTransactionId');
            // generateTransactionId();
            // document.querySelector('#productRows').innerHTML = '';
        }

        // دالة لحذف صف منتج
        function removeProductRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        // دالة لتحديث بيانات صف منتج معين باستخدام AJAX
        function updateRow(button) {
            const row = button.closest('tr');
            const product = row.querySelector('[name="products[]"]').value;
            const quantity = row.querySelector('[name="quantities[]"]').value;
            const unitPrice = row.querySelector('[name="unit_prices[]"]').value;
            const total = row.querySelector('[name="totals[]"]').value;

            // مثال على استدعاء AJAX (يمكنك تعديل الـ endpoint والبيانات حسب احتياجاتك)
            fetch('/inventory/transactions/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product: product,
                        quantity: quantity,
                        unitPrice: unitPrice,
                        total: total
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم تحديث الصف بنجاح');
                    } else {
                        alert('حدث خطأ أثناء التحديث');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
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

            // جمع إجماليات كل منتج بعد الخصم الفردي
            document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
                let quantity = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
                console.log(quantity);
                let price = parseFloat(row.querySelector('.price-input')?.value) || parseFloat(row.querySelector(
                    '.unit-price-input')?.value) || 0;
                // let discount = parseFloat(row.querySelector('.item-discount-input')?.value) || 0;
                totalPrice += (quantity * price); // - discount;
            });

            let exchangeRate = parseFloat(document.getElementById('exchange_rate').value) || 1;
            let convertedTotalPrice = totalPrice * exchangeRate;

            document.getElementById('amount_before_discount').value = convertedTotalPrice.toFixed(2);

            let discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
            let discountType = document.getElementById('discount_type').value;
            let discountAmount = 0;

            if (discountType === '2') { // نسبة
                discountAmount = convertedTotalPrice * (discountValue / 100);
                convertedTotalPrice -= discountAmount;
            } else { // قيمة ثابتة
                discountAmount = discountValue;
                convertedTotalPrice -= discountValue;
            }

            document.getElementById('discount_amount').value = discountAmount.toFixed(2);
            document.getElementById('total_amount').value = convertedTotalPrice.toFixed(2);
        }
        document.querySelector('#invoice-items-table').addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                let selectedProductId = e.target.value;
                let row = e.target.closest('tr');
                let unitSelect = row.querySelector('.units-select');
                console.log('usnt', selectedProductId);
                if (selectedProductId) {
                    fetch(`/get-units/${selectedProductId}`)
                        .then(response => response.json())
                        .then(data => {
                            unitSelect.innerHTML = '<option value="">اختر وحدة</option>';
                            data.units.forEach(unit => {
                                unitSelect.innerHTML +=
                                    `<option value="${unit.id}">${unit.name}</option>`;
                            });
                        });
                }
            }
        });
    </script>
</x-layout>
