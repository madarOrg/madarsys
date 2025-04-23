<x-layout>
    <section>
        <div>
            <x-title :title="'إضافة منتج إلى المستودع '"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال مكان المنتج بدقة.
            </p>
            <form action="{{ route('inventory-products.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
                    <input type="hidden" name="distribution_type" value="1">

                    {{-- <div class="mb-4">
                        <label for="batch_number" class="form-label">رقم الدفعة</label>
                        <div class="form-control bg-light" id="batch_number">
                            {{ $batch_number ?? 'يتم توليده تلقائيًا' }}
                        </div>
                    </div> --}}
                    <div class="mb-4">
                        <label for="batch_number" class="block text-sm font-medium text-gray-700 form-label">رقم الدفعة</label>
                        <input type="text" class="form-control bg-light w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" id="batch_number" name="batch_number"
                               value="{{ $batch_number ?? 'يتم توليده تلقائيًا' }}" readonly>
                    </div>
                    
                    
                    <!-- اختيار المستودع -->
                    <div class="mt-2">
                        <label for="warehouse_id" class=" block text-sm font-medium text-gray-700">المستودع</label>
                        <select id="warehouse_id" name="warehouse_id" class="tom-select w-full">
                            <option value="">اختر مستودعًا</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <!-- اختيار الحركة المخزنية -->
                    <div class="mb-4">
                        <div class="mt-2">
                            <label for="inventory_transaction_item_id"
                            class="block text-sm font-medium text-gray-700">الحركة المخزنية</label>
                            <select id="inventory_transaction_item_id" name="inventory_transaction_item_id"
                                class="tom-select w-full"
                                data-route="{{ url('/get-inventory-transactions/${warehouseId}') }}">
                                @foreach ($transactions as $transaction)
                                    {{-- <option value="{{ $transaction->id }}" --}}

                                    data-product-id="{{ $transaction->product_id }}">
                                    {{ $transaction->reference }} - {{ $transaction->batch_number }} - {{ $transaction->product_name }} -
                                    {{ $transaction->sku }} -  {{ $transaction->barcode }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="productUnitName">{{  $transaction->unit->name ?? '---' }}</span>

                        </div>

                    </div>
                    <div class="mb-4">
                        <x-file-input type="text" id="product_id" name="product_id" label="رقم المنتج" readonly />
                    </div>
                    
                    <div class="mb-4">
                        <x-file-input type="timestamp" id="production_date" name="production_date" label="تاريخ الإنتاج"  />
                    </div>
                    
                    <div class="mb-4">
                        <x-file-input type="timestamp" id="expiration_date" name="expiration_date" label="تاريخ الانتهاء"  />
                    </div>
                    
                   
                    <div class="mb-4 flex items-center gap-2">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية" required min="1" />
                        <span style="font-size: 60px; font-weight: bold;" class="mt-4">/</span>
                        <span id="quantityOfProduct"  class=" bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-4"
                        >0</span>
                        <span id="transactionUnitName" class="text-sm text-gray-500 dark:text-gray-400 ml-2"></span>

                     </div>
                    

                    <!-- اختيار المنطقة التخزينية -->
                    <div class="mb-4">
                        <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية"
                            :options="$storageAreas->pluck('area_name', 'id')" required />
                    </div>

                    <!-- إدخال الموقع الدقيق للرف -->
                    <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج" :options="$locations"
                            required />
                    </div>

                    <!-- توزيع الكمية على المناطق/الرفوف -->
                    <div id="quantity-distribution">
                        <!-- سيتم ملؤها باستخدام JavaScript -->
                    </div>

                </div>

                <div class="sm:col-span-6 flex justify-end mt-6">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </form>
        </div>
    </section>


</x-layout>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // تهيئة TomSelect لمستودع الحركات المخزنية
    const warehouseSelect = new TomSelect('#warehouse_id', {
        create: false,
        searchField: 'text',
        placeholder: 'اختر مستودعًا',
    });

    const inventoryTransactionSelect = new TomSelect('#inventory_transaction_item_id', {
        create: false,
        placeholder: 'اختر الحركة المخزنية',
        searchField: 'text',
    });

    const productIdInput = document.getElementById('product_id');
    const productionDateInput = document.getElementById('production_date');
    const expirationDateInput = document.getElementById('expiration_date');
    const quantityInput = document.getElementById('quantityOfProduct'); // إضافة الكمية
    const unitNameSpan = document.getElementById('productUnitName');

    
    // دالة لجلب الحركات المخزنية بناءً على المستودع المحدد
    async function loadTransactions(warehouseId) {
        if (!warehouseId) {
            inventoryTransactionSelect.clear();
            inventoryTransactionSelect.clearOptions();
            return;
        }

        const route = `/get-inventory-transactions/${warehouseId}`;
        try {
            const response = await fetch(route, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            });

            if (!response.ok) throw new Error('Failed to fetch data');

            const data = await response.json();

            // تحقق من أن البيانات مصفوفة
            if (Array.isArray(data) && data.length > 0) {
                inventoryTransactionSelect.clear();
                inventoryTransactionSelect.clearOptions();

                data.forEach(item => {
                    inventoryTransactionSelect.addOption({
                        value: item.id,
                        text: `${item.reference} - ${item.product_name}- ${item.sku}- ${item.barcode}`
                    });
                    // quantityInput.value = item.quantity || ''; 
                    // console.log('fetching product:',   item.quantity )

                });
            } else {
                console.log('No transactions found for this warehouse');
            }
        } catch (error) {
            console.error('Error loading transactions:', error);
        }
    }


    // عند تغيير المستودع
    warehouseSelect.on('change', (value) => loadTransactions(value));

    // عند اختيار حركة مخزنية يتم تعبئة الحقول
    inventoryTransactionSelect.on('change', async (transactionId) => {
        if (transactionId) {
            try {
                console.log('fetching product:', transactionId);

                const response = await fetch(`/get-product-inventory/${transactionId}`);

                if (!response.ok) throw new Error('Failed to fetch product');

                const product = await response.json();
                if (product) {

                    productIdInput.value = product.product_id || '';
                    productionDateInput.value = product.production_date || '';
                    expirationDateInput.value = product.expiration_date || '';
                    quantityInput.innerText = product.quantity || '';
                    unitNameSpan.innerText = product.unit_name || '---'; // عرض اسم الوحدة

                    // document.getElementById('quantityOfProduct').innerText = product.quantity || '0';
document.getElementById('transactionUnitName').innerText = product.transaction_unit_name || '---';

                    console.log('fetching product:', product.quantity );

                }
            } catch (error) {
                console.error('Error fetching product:', error);
            }
        } else {
            productIdInput.value = '';
            productionDateInput.value = '';
            expirationDateInput.value = '';
            quantityInput.value = ''; // إعادة تعيين الكمية عند إلغاء التحديد

        }
    });
});

</script>
