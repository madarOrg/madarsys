 <x-layout>

    <section class="mb-24 p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Since we're updating, we need to specify the PUT method -->
    
            <div class="space-y-12">
                <div class="pb-0">
                    <x-title :title="'تعديل فاتورة مبيعات'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى تعديل بيانات الفاتورة كما يلزم.
                    </p>
                </div>
    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Selection (Pre-populate with the current customer) -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">العميل</label>
                        <select name="customer_id" id="customer_id" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                            <option value="">اختر العميل</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
    
                    <!-- Invoice Date (Pre-populate with the existing invoice date) -->
                    <div>
                        <x-file-input id="invoice_date" name="invoice_date" label="تاريخ الفاتورة" type="date" value="{{ $invoice->invoice_date }}" required="true" />
                        @error('invoice_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method -->
                    <div>
                        <label for="payment_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                        <select name="payment_type_id" id="payment_type_id" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                            <option value="">اختر طريقة الدفع</option>
                            @foreach($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}" {{ $invoice->payment_type_id == $paymentType->id ? 'selected' : '' }}>
                                    {{ $paymentType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_type_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
    
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-6">تفاصيل الفاتورة</h4>
    
                <!-- Table for Invoice Items (Pre-populate with the existing items) -->
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
                            @foreach ($invoice->items as $index => $item)
                                <tr>
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    <td class="py-2 px-4">
                                        <select name="items[{{ $index }}][product_id]" class="product-select w-full p-2 border rounded-lg focus:ring focus:ring-blue-500"  required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 px-4">
                                        <input type="number" name="items[{{ $index }}][price]" class="price-input w-full py-2 px-4 border rounded" value="{{ $item->price }}" required>
                                    </td>
                                    <td class="py-2 px-4">
                                        <input type="number" name="items[{{ $index }}][quantity]" class="quantity-input w-full py-2 px-4 border rounded" value="{{ $item->quantity }}" required>
                                    </td>
                                    <td class="py-2 px-4">
                                        <button type="button" class="remove-item text-red-600 hover:text-red-800">إزالة</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
    
                <div class="flex justify-start mt-4">
                    <x-button type="button" id="add-item" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">إضافة منتج</x-button>
                </div>

                <div class="mt-6">
                    <label for="total_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">المجموع الكلي</label>
                    <input type="text" id="total_price" name="total_price" value="{{ $invoice->total_amount }}" readonly class="w-full p-2 border rounded-lg">
                </div>
    
                <div class="flex justify-start mt-4">
                    <x-button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">تعديل الفاتورة</x-button>
                </div>
            </div>
        </form>
    </section>
    
    
<script>
    // Ensure products data is loaded from the Blade view
    let products = @json($products);

    // Function to update the total price
    function updateTotalPrice() {
        let totalPrice = 0;
        document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
            let quantity = row.querySelector('[name*="[quantity]"]').value || 0;
            let price = row.querySelector('[name*="[price]"]').value || 0;
            totalPrice += (quantity * price);
        });
        document.getElementById('total_price').value = totalPrice.toFixed(2);
    }

    // Add event listener to "Add Item" button
    document.getElementById('add-item').addEventListener('click', function () {
        let index = document.querySelectorAll('#invoice-items-table tbody tr').length;

        // Create product options dynamically from the products array
        let productOptions = products.map(product =>
            `<option value="${product.id}" data-price="${product.selling_price}">${product.name}</option>`
        ).join('');

        // Create a new row for the table
        let newRow = `
            <tr>
            <td class="py-2 px-4">
                <select name="items[${index}][product_id]" class="product-select w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                    <option value="">اختر المنتج</option>
                    ${productOptions}
                </select>
            </td>
                <td class="py-2 px-4">
                    <input type="number" name="items[${index}][price]" class="price-input w-full py-2 px-4 border rounded" readonly required>
                </td>
                <td class="py-2 px-4">
                    <input type="number" name="items[${index}][quantity]" class="quantity-input w-full py-2 px-4 border rounded" min="1" value="1" required>
                </td>
                <td class="py-2 px-4">
                    <button type="button" class="remove-item text-red-600 hover:text-red-800">إزالة</button>
                </td>
            </tr>`;

        document.querySelector('#invoice-items-table tbody').insertAdjacentHTML('beforeend', newRow);
        updateTotalPrice();
    });

    // Event delegation for selecting products
    document.querySelector('#invoice-items-table').addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('product-select')) {
            let selectedOption = e.target.options[e.target.selectedIndex];
            let priceInput = e.target.closest('tr').querySelector('.price-input');
            priceInput.value = selectedOption.getAttribute('data-price') || 0;
            updateTotalPrice();
        }
    });

    // Event delegation for removing items
    document.querySelector('#invoice-items-table').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
            updateTotalPrice();
        }
    });

    // Event delegation for updating total when quantity changes
    document.querySelector('#invoice-items-table').addEventListener('input', function (e) {
        if (e.target && e.target.classList.contains('quantity-input')) {
            updateTotalPrice();
        }
    });
</script>



</x-layout>





