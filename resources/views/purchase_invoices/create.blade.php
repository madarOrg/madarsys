


 <x-layout>
    <section class="mb-24 p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        <form action="{{ route('purchase_invoices.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div class="pb-6">
                    <x-title :title="'إنشاء فاتورة مشتريات جديدة'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات الفاتورة بدقة لضمان تنظيم العمل مع الفواتير.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Selection -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">العميل</label>
                        <select name="supplier_id" id="supplier_id" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                            <option value="">اختر المورد</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Invoice Date -->
                    <div>
                        <x-file-input id="invoice_date" name="invoice_date" label="تاريخ الفاتورة" type="date" required="true" />
                    </div>
                </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                            <select name="payment_type_id" id="payment_type_id" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
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
                
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-6">تفاصيل الفاتورة</h4>

                <!-- Table for Invoice Items -->
                <div class="overflow-x-auto">
                    <table id="invoice-items-table" class="w-full border-collapse border rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <th class="p-3">المنتج</th>
                                <th class="p-3">السعر</th>
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
                    <x-button type="button" id="add-item" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">إضافة منتج</x-button>
                </div>
                
                
                <div class="mt-6">
                    <label for="total_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">المجموع الكلي</label>
                    <input type="text" id="total_price" name="total_price" value="0" readonly class="w-full p-2 border rounded-lg">
                </div>

                <div class="flex justify-start mt-4">
                    <x-button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ الفاتورة</x-button>
                </div>
            </div>
        </form>
    </section>

    <script>
        let products = @json($products);

        function updateTotalPrice() {
            let totalPrice = 0;
            document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
                let quantity = row.querySelector('.quantity-input').value || 0;
                let price = row.querySelector('.price-input').value || 0;
                totalPrice += (quantity * price);
            });
            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }

        document.getElementById('add-item').addEventListener('click', function () {
            let index = document.querySelectorAll('#invoice-items-table tbody tr').length;
            let productOptions = products.map(product => `<option value="${product.id}" data-price="${product.selling_price}">${product.name}</option>`).join('');
            let newRow = `
                <tr>
                <td class="py-2 px-4">
                    <select name="items[${index}][product_id]" class="product-select w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                        <option value="">اختر المنتج</option>
                        ${productOptions}
                    </select>
                </td>
                    <td class="p-3">
                        <input type="number" name="items[${index}][price]" class="price-input w-full p-2 border rounded-lg" required>
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

// Event listener to handle product selection (set price when product is chosen)
document.querySelector('#invoice-items-table').addEventListener('change', function (e) {
    if (e.target.classList.contains('product-select')) {
        let selectedOption = e.target.options[e.target.selectedIndex];
        let priceInput = e.target.closest('tr').querySelector('.price-input');
        priceInput.value = selectedOption.getAttribute('data-price') || 0;  // Set price from product data
        updateTotalPrice();  // Update total price
    }
});


// Event listener to update total when quantity or price changes
document.querySelector('#invoice-items-table').addEventListener('input', function (e) {
    if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
        updateTotalPrice();  // Update total price when quantity or price is changed
    }
});

        document.querySelector('#invoice-items-table').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('tr').remove();
                updateTotalPrice();
            }
        });
    </script>
</x-layout>
