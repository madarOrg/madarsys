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

                    <!-- إضافة الحقول الثلاثة -->
                    <div class="mb-4">
                        <x-file-input type="text" id="batch_number" name="batch_number" label="رقم الدفعة" />
                    </div>
                    <!-- اختيار المستودع -->
                    <div class="mb-4">
                        <select id="warehouse_id" name="warehouse_id" class="tom-select w-full">
                            <option value="">اختر مستودعًا</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <!-- اختيار الحركة المخزنية -->
                    <div class="mb-4">
                        <div class="mb-4">
                            <select id="inventory_transaction_item_id" name="inventory_transaction_item_id"
                                class="tom-select w-full"
                                data-route="{{ url('/get-inventory-transactions/${warehouseId}') }}">
                                @foreach ($transactions as $transaction)
                                    {{-- <option value="{{ $transaction->id }}" --}}
                                    data-product-id="{{ $transaction->product_id }}">
                                    {{ $transaction->reference }} - {{ $transaction->product_name }} -
                                    {{ $transaction->suk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- اختيار المنتج -->
                    {{-- <div class="mb-4">
                        <x-select name="product_id" :options="$products->toArray()" :route="url('/api/search/products')" />

                    </div> --}}

                    <div class="mb-4">
                        <x-file-input type="date" id="production_date" name="production_date"
                            label="تاريخ الإنتاج" />
                    </div>
                    <div class="mb-4">
                        <x-file-input type="date" id="expiration_date" name="expiration_date"
                            label="تاريخ الانتهاء" />
                    </div>
                    <div class="mb-4">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية" required
                            min="1" />
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
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة TomSelect مع القيم المحملة مسبقًا من Blade
        new TomSelect('#warehouse_id', {
            create: false, // منع إنشاء خيارات جديدة
            searchField: 'text',
            placeholder: 'اختر مستودعًا',
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inventoryTransactionSelect = document.getElementById(
            'inventory_transaction_item_id'); // تغيير الاسم
        const warehouseSelect = document.getElementById('warehouse_id');

        // تهيئة TomSelect للحركات المخزنية
        const tomSelectInstance = new TomSelect(inventoryTransactionSelect, {
            create: false,
            placeholder: 'اختر الحركة المخزنية',
            searchField: 'text',
        });

        // دالة لجلب الحركات المخزنية بناءً على المستودع المحدد
        async function loadTransactions(warehouseId) {
            if (!warehouseId) {
                tomSelectInstance.clear();
                tomSelectInstance.clearOptions();
                return;
            }

            const route = `/get-inventory-transactions/${warehouseId}`;

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
                console.log('Fetched Data:', data); // لفحص البيانات

                // تحقق من أن البيانات مصفوفة
                if (Array.isArray(data) && data.length > 0) {
                    // تفريغ الخيارات القديمة وتحميل الخيارات الجديدة
                    tomSelectInstance.clear();
                    tomSelectInstance.clearOptions();

                    // إضافة الخيارات الجديدة
                    data.forEach(item => {
                        tomSelectInstance.addOption({
                            value: item.id,
                            text: `${item.reference} - ${item.product_name}`
                        });
                    });
                } else {
                    console.log('No transactions found for this warehouse');
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
            }
        }

        // تحديث الحركات عند تغيير المستودع
        warehouseSelect.addEventListener('change', (e) => loadTransactions(e.target.value));
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
</script>
