<x-layout>
    <section class="mb-24 p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        <form action="{{ route('invoices.store', ['type' => 'purchase']) }}" method="POST">
            @csrf
            <div class="space-y-12  dark:bg-gray-900 mb-24">
                <div class="col-span-1">
                    <x-title :title="'إنشاء فاتورة مبيعات جديدة'"></x-title>
                </div>
                <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                    <div class="col-span-1">
                        <x-file-input id="invoice_Code" value="{{ request('invoice_code') ?? '' }}" name="invoice_Code"
                            readonly label="رقم الفاتورة" />
                        @error('invoice_Code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-1">
                        <label for="branch_id"
                            class="text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                        <select name="branch_id" id="branch_id"
                            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                            required>
                            <option value="">اختر الفرع</option>
                            @foreach ($Branchs as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label for="warehouse_id"
                            class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                        <select name="warehouse_id" id="warehouse_id"
                            class="w-full bg-gray-100 rounded border py-1 px-3 leading-8 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500"
                            required>
                            <option value="" selected>اختر المستودع</option>
                            @foreach($Warehouses as $Warehouse)
                                <option value="{{ $Warehouse->id }}">{{ $Warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- partner Selection -->
                    <div class="col-span-1">
                        <label for="partner_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">العميل</label>
                        <select name="partner_id" id="partner_id"
                            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                            required>
                            <option value="">اختر العميل</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                        @error('partner_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Invoice Date -->
                    <div class="col-span-1">
                        <x-file-input id="invoice_date" name="invoice_date" label="تاريخ الفاتورة" type="date"
                            required="true" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" />
                        @error('invoice_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-1">
                        <!-- Payment Method -->
                        <div>
                            <label for="payment_type_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                            <select name="payment_type_id" id="payment_type_id"
                                class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                required>
                                <option value="">اختر طريقة الدفع</option>
                                @foreach($paymentTypes as $paymentType)
                                    <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                @endforeach
                            </select>
                            @error('payment_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label for="currency_id"
                            class="block text-sm font-medium text-gray-600 dark:text-gray-400">العملة</label>
                        <select name="currency_id" id="currency_id"
                            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                            required>
                            @foreach($currencies as $index => $currency)
                                <option value="{{ $currency->id }}" {{ $index == 0 ? 'selected' : '' }}>{{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="col-span-1">
                        <x-file-input id="exchange_rate" name="exchange_rate" label="سعر الصرف" type="number"
                            step="0.0001" required="true" value="1" />
                        @error('exchange_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-1/3">
                            <label for="discount_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الخصم</label>
                            <select id="discount_type" name="discount_type"
                                class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                <option value="1">مبلغ</option>
                                <option value="2">نسبة</option>
                            </select>
                        </div>

                        <div class="flex-1">
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
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-6">تفاصيل الفاتورة</h4>
                <div class="overflow-x-auto">
                    <table id="invoice-items-table" class="w-full border-collapse border rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <th class="p-3">المنتج</th>
                                <th class="p-3">السعر</th>
                                <th class="p-3">الوحدة</th>
                                <th class="p-3">الكمية</th>
                                <th class="p-3">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Items will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-start mt-4">
                    <x-button type="button" id="add-item"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">إضافة منتج</x-button>
                </div>
                <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                    <div class="col-span-1">
                        <x-file-input id="amount_before_discount" name="amount_before_discount" value="0" readonly
                            label="الاجمالي قبل الخصم " />
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
        let products = @json($products);
        let units = @json($units);

        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'product_id') {
                let productId = e.target.value;
                let unitselect = document.getElementById('unit_id');
                let selectedProduct = products.find(product => product.id == productId);

                if (selectedProduct && unitselect) {
                    let unitId = selectedProduct.unit_id;
                    unitselect.value = unitId;
                }
            }
        });

        document.getElementById('add-item').addEventListener('click', function () {
            let index = document.querySelectorAll('#invoice-items-table tbody tr').length;

            let productOptions = products.map(product =>
                `<option value="${product.id}" data-price="${product.selling_price}" data-unit-id="${product.unit_id}">
            ${product.name}
        </option>`
            ).join('');

            let UnitOptions = units.map(Unit =>
                `<option value="${Unit.id}" data-id="${Unit.id}">${Unit.name}</option>`
            ).join('');

            let newRow = `
        <tr>
            <td class="py-2 px-4">
                <select name="items[${index}][product_id]" class="product-select w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                    <option value="">اختر المنتج</option>
                    ${productOptions}
                </select>
            </td>
            <td class="p-3">
                <input type="number" step="any" min="0" name="items[${index}][price]" class="price-input w-full p-2 border rounded-lg" required>
            </td>
            <td class="py-2 px-4">
                <select name="items[${index}][unit_id]" class="unit-select w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                    <option value="">اختر الوحدة</option>
                    ${UnitOptions}
                </select>
            </td>
            <td class="p-3">
                <input type="number" name="items[${index}][quantity]" class="quantity-input w-full p-2 border rounded-lg" min="1" value="1" required>
            </td>
            <td class="p-3">
                <button type="button" class="remove-item text-red-600 hover:text-red-800">إزالة</button>
            </td>
        </tr>`;

            document.querySelector('#invoice-items-table tbody').insertAdjacentHTML('beforeend', newRow);
            updateTotalPrice();
        });

        document.querySelector('#invoice-items-table').addEventListener('change', function (e) {
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

        document.querySelector('#invoice-items-table').addEventListener('input', function (e) {
            if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
                updateTotalPrice();
            }
        });

        document.querySelector('#invoice-items-table').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('tr').remove();
                updateTotalPrice();
            }

        });
        document.getElementById('exchange_rate').addEventListener('input', updateTotalPrice);
        document.getElementById('discount_value').addEventListener('input', updateTotalPrice);
        document.getElementById('discount_type').addEventListener('change', updateTotalPrice);

        document.getElementById('payment_type_id').addEventListener('change', function () {
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

    </script>
</x-layout>