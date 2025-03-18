 <x-layout>

    <section class="mb-24 p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        <form action="{{ route('invoices.update', ['type' => $invoice->type, 'invoice' => $invoice->id]) }}" method="POST">
            @csrf
            @method('PUT') <!-- Since we're updating, we need to specify the PUT method -->
            <div class="space-y-12">
                <div class="pb-0">
                    <x-title :title="'تعديل فاتورة مبيعات'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى تعديل بيانات الفاتورة كما يلزم.
                    </p>
                </div>
                <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">

                    <div class="col-span-1">
                        <label for="invoice_Code" class="text-sm font-medium text-gray-600 dark:text-gray-400">رقم الفاتورة</label>
                        <input type="text" id="invoice_Code" name="invoice_Code" value="{{ $invoice->invoice_code }}" 
                            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" 
                            readonly />
                        @error('invoice_Code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    
                    <div class="col-span-1">
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
                    </div>
                    
                    <!-- partner Selection -->
                    <div class="col-span-1">
                        <label for="partner_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">العميل</label>
                        <select name="partner_id" id="partner_id" class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" required>
                            <option value="">اختر العميل</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}" {{ $invoice->partner_id == $partner->id ? 'selected' : '' }}>
                                    {{ $partner->name }} <!-- Assuming the partner has a 'name' attribute -->
                                </option>
                            @endforeach
                        </select>
                        
                        @error('partner_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Invoice Date -->
                    <div class="col-span-1">
                        <x-file-input id="invoice_date" value="{{ $invoice->invoice_date }}" name="invoice_date" label="تاريخ الفاتورة" type="date" required="true" />
                        @error('invoice_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                <div class="col-span-1">
                    <!-- Payment Method -->
                    <div>
                        <label for="payment_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                        <select name="payment_type_id" id="payment_type_id" class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"                        
                            required>
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
                <div class="flex items-center gap-4">
                    <div class="w-1/3">
                        <label for="discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الخصم</label>
                        <select id="discount_type" name="discount_type" class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                            <option value="1" {{ old('discount_type', $invoice->discount_type) == '1' ? 'selected' : '' }}>مبلغ</option>
                            <option value="2" {{ old('discount_type', $invoice->discount_type) == '2' ? 'selected' : '' }}>نسبة</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="discount_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">القيمة</label>
                        <input type="text" id="discount_value" name="discount_value" value="{{ old('discount_value', $invoice->discount_type == '2' ? $invoice->discount_percentage : $invoice->discount_amount) }}" class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" placeholder="قيمة الخصم">
                    </div>
                </div>
                <div class="col-span-1">
                    <div id="check-number-container" class="hidden">
                        <label for="check_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الشيك</label>
                        <input type="text" value="{{ $invoice->check_number }}"  name="check_number" id="check_number" class="w-full bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">
                        @error('check_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
                <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                    <!-- Discount Amount -->
                    <div class="col-span-1">
                        <x-file-input id="discount_amount" name="discount_amount" value="{{ $invoice->discount_amount }}" readonly label="مبلغ الخصم" />
                        @error('discount_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Total Price -->
                    <div class="col-span-1">
                        <x-file-input id="total_price" name="total_price" value="{{ $invoice->total_amount }}" readonly label="المجموع الكلي" />
                        @error('total_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                </div>
            </div>
            <div class="flex justify-start mt-4">
                <x-button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">تعديل الفاتورة</x-button>
            </div>
        </form>
    </section>
    <script>
        let products = @json($products);
        function updateTotalPrice() {
    let totalPrice = 0;
    let discountAmount = 0;
    // Calculate the total price based on the items
    document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
        let quantity = row.querySelector('.quantity-input').value || 0;
        let price = row.querySelector('.price-input').value || 0;
        totalPrice += (quantity * price);
    });
    // Get the discount value and type
    let discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
    let discountType = document.getElementById('discount_type').value;
    // Calculate the discount amount and apply it to the total price
    if (discountType === '2') {
        discountAmount = totalPrice * (discountValue / 100); // Calculate discount as a percentage of total price
        totalPrice = totalPrice * (1 - discountValue / 100);
    } else {
        discountAmount = discountValue; // Use the value directly if it's an amount
        totalPrice -= discountValue;
    }
    // Set the discount amount in the corresponding input field
    document.getElementById('discount_amount').value = discountAmount.toFixed(2);
    // Set the final total price
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
                        <input type="number" step="any" min="0"  name="items[${index}][price]" class="price-input w-full p-2 border rounded-lg" required>
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


   document.addEventListener('DOMContentLoaded', function () {
        const paymentTypeSelect = document.getElementById('payment_type_id');
        const checkNumberContainer = document.getElementById('check-number-container');

        // Function to handle showing or hiding the check number container
        function handlePaymentTypeChange() {
            const selectedPaymentType = paymentTypeSelect.value;
            if (selectedPaymentType == '4') {
                checkNumberContainer.classList.remove('hidden');
            } else {
                checkNumberContainer.classList.add('hidden');
            }
        }

        // Initialize on page load
        handlePaymentTypeChange();

        // Add the event listener for change
        paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
    });
        </script>

</x-layout>





