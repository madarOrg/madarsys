<x-layout>
    <section>
        <div>
            <x-title :title="'سحب منتج إمن المستودع '"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال مكان المنتج بدقة.
            </p>
            <form action="{{ route('inventory-products.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
                    <input type="hidden" name="distribution_type" value="{{ request('distribution_type', '-1') }}">

                    <div class="mb-4">
                        <x-file-input label="المستودع" id="warehouse_name" value="{{ $product->warehouse->name }}"
                            readonly :model="null" name="warehouse_id" type="text" />
                        <input type="hidden" id="warehouse_id" name="warehouse_id"
                            value="{{ $product->warehouse->id }}">
                    </div>


                    <!-- الحركة المخزنية -->
                    <div class="mb-4">
                        <x-file-input label="الحركة المخزنية" id="inventory_transaction_item_id"
                            value="{{ $transactionItem->id }}-{{ $transactionItem->inventoryTransaction->reference }}"
                            readonly :model="null" name="inventory_transaction_item_id" type="text" />
                        <input type="hidden" name="inventory_transaction_item_id" value="{{ $transactionItem->id }}">
                    </div>

                    <!-- المنتج -->
                    <div class="mb-4">
                        <x-file-input label="المنتج" id="product_id" value="{{ $product->product->name }}" readonly
                            :model="null" name="product_id" type="text" />
                        <input type="hidden" name="product_id" value="{{ $product->product->id }}">
                    </div>

                    <!-- رقم الدفعة -->
                    <div class="mb-4">
                        <x-file-input label="رقم الدفعة" id="batch_number" value="{{ $product->batch_number }}" readonly
                            :model="null" name="batch_number" type="text" />
                        <input type="hidden" name="batch_number" value="{{ $product->batch_number }}">
                    </div>

                    <!-- تاريخ الإنتاج -->
                    <div class="mb-4">
                        <x-file-input type="date" id="production_date" name="production_date" label="تاريخ الإنتاج"
                            value="{{ $product->production_date }}" readonly />
                        <input type="hidden" name="production_date" value="{{ $product->production_date }}">
                    </div>

                    <!-- تاريخ الانتهاء -->
                    <div class="mb-4">
                        <x-file-input type="date" id="expiration_date" name="expiration_date" label="تاريخ الانتهاء"
                            value="{{ $product->expiration_date }}" readonly />
                        <input type="hidden" name="expiration_date" value="{{ $product->expiration_date }}">
                    </div>

                    <!-- الكمية -->
                    <!-- عرض الكمية الحالية -->
                    <div class="mb-4">
                        <x-file-input type="number" id="quantity_display" name="quantity_display"
                            label="الكمية المتاحة" :value="$product->quantity" readonly />
                    </div>

                    <!-- حقل إدخال الكمية المراد سحبها -->
                    <div class="mb-4">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية المراد سحبها" required
                            min="1" />
                    </div>

                    <!-- تمرير الكمية القديمة كقيمة مخفية -->
                    <input type="hidden" name="old_quantity" value="{{ $product->quantity }}">

                    <!-- المنطقة التخزينية -->
                    <div class="mb-4">
                        <x-file-input label="المنطقة التخزينية" id="storage_area_id"
                            value="{{ $product->storageArea->area_name }}" readonly :model="null"
                            name="storage_area_id" type="text" />
                        <input type="hidden" name="storage_area_id" value="{{ $product->storage_area_id }}">
                    </div>

                    <!-- الموقع (الرف) -->
                    <div class="mb-4">
                        <x-file-input label="موقع المنتج" id="location_id" value="{{ $product->location->rack_code }}"
                            readonly :model="null" name="location_id" type="text" />
                        <input type="hidden" name="location_id" value="{{ $product->location_id }}">
                    </div>

                </div>
                <!-- زر الحفظ -->
                <div class="sm:col-span-6 flex justify-end mt-6">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
{{-- <script>
    // إضافة حدث عند تغيير تاريخ الانتهاء
    document.getElementById('expiration_date').addEventListener('change', function() {
        // الحصول على قيم التاريخين
        const productionDate = document.getElementById('production_date').value;
        const expirationDate = this.value;

        // التحقق إذا كان تاريخ الانتهاء قبل تاريخ الإنتاج
        if (new Date(expirationDate) < new Date(productionDate)) {
            alert('تاريخ الانتهاء لا يمكن أن يكون قبل تاريخ الإنتاج.');
            this.value = '';  // مسح الحقل إذا كانت القيمة غير صالحة
        }
    });
</script> --}}

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const warehouseHiddenInput = document.getElementById('warehouse_id');
    const transactionSelect = document.getElementById('inventory_transaction_item_id');
    let tomSelectInstance = new TomSelect(transactionSelect);

    async function loadTransactions(warehouseId) {
        console.log('Warehouse ID:', warehouseId);

        if (!warehouseId) {
            tomSelectInstance.clear();
            tomSelectInstance.clearOptions();
            return;
        }

        const route = `/get-inventory-transactions-out/${warehouseId}`;
        
        try {
            const response = await fetch(route, {
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                }
            });

            if (!response.ok) throw new Error('Failed to fetch data');

            const data = await response.json();
            console.log('Fetched Data:', data);

            if (Array.isArray(data) && data.length > 0) {
                tomSelectInstance.clear();
                tomSelectInstance.clearOptions();

                data.forEach(item => {
                    tomSelectInstance.addOption({
                        value: item.id,
                        text: `${item.reference} - ${item.product_name}`
                    });
                });

                tomSelectInstance.refreshOptions();
            } else {
                console.log('No transactions found for this warehouse');
            }
        } catch (error) {
            console.error('Error loading transactions:', error);
        }
    }

    // تحميل الحركات عند تحميل الصفحة
    const warehouseId = warehouseHiddenInput.value;
    loadTransactions(warehouseId);
});



    document.addEventListener('DOMContentLoaded', function() {
        const transactionSelect = document.getElementById('inventory_transaction_item_id');
        const productSelect = document.getElementById('product_id'); // تأكد أن هذا هو ID لحقل المنتج

        transactionSelect.addEventListener('change', function() {
            // الحصول على قيمة الـ product_id المرتبطة بالحركة المخزنية المختارة
            const selectedOption = transactionSelect.options[transactionSelect.selectedIndex];
            const productId = selectedOption.getAttribute('data-product-id');

            // تحديث حقل product_id
            productSelect.value = productId; // تعيين القيمة للـ product_id
        });
    });
</script> --}}
