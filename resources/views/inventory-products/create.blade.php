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
                        <label for="batch_number" class="block text-sm font-medium text-gray-700 form-label">رقم
                            الدفعة</label>
                        <input type="text"
                            class="form-control bg-light w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                            id="batch_number" name="batch_number" value="{{ $batch_number ?? 'يتم توليده تلقائيًا' }}"
                            readonly>
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
                                    {{ $transaction->reference }} - {{ $transaction->batch_number }} -
                                    {{ $transaction->product_name }} -
                                    {{ $transaction->sku }} - {{ $transaction->barcode }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="productUnitName">{{ $transaction->unit->name ?? '---' }}</span>

                        </div>

                    </div>
   
                    <!-- اختيار المنطقة التخزينية -->
                    {{-- <div class="mb-4">
                        <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية"
                            :options="$storageAreas->pluck('area_name', 'id')" required />
                    </div>

                    <!-- إدخال الموقع الدقيق للرف -->
                    <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج" :options="$locations"
                            required />
                    </div> --}}
                    {{-- المنطقة التخزينية --}}
                    <div class="mb-4">
                        <label for="storage_area_id" class="block text-sm font-medium text-gray-700">المنطقة
                            التخزينية</label>
                        <select id="storage_area_id" name="storage_area_id" class="tom-select w-full">
                            <option value="">اختر منطقة</option>
                            @foreach ($storageAreas as $area)
                                <option value="{{ $area->id }}"
                                    {{ request('storage_area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->area_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('storage_area_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الموقع --}}
                    <div class="mb-4">
                        <label for="location_id" class="block text-sm font-medium text-gray-700">موقع المنتج</label>
                        <select id="location_id" name="location_id" class="tom-select w-full">
                            <option value="">اختر موقعاً</option>
                            {{-- نملأ هذا القسم أوليًّا بقيم $locations القادمة من الكونترولر --}}
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}"
                                    {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->rack_code }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-file-input type="text" id="product_id" name="product_id" label="رقم المنتج" readonly />
                    </div>

                    <div class="mb-4">
                        <x-file-input type="timestamp" id="production_date" name="production_date"
                            label="تاريخ الإنتاج" />
                    </div>

                    <div class="mb-4">
                        <x-file-input type="timestamp" id="expiration_date" name="expiration_date"
                            label="تاريخ الانتهاء" />
                    </div>


                    <div class="mb-4 flex items-center gap-2">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية" required
                            min="1" />
                        <span style="font-size: 60px; font-weight: bold;" class="mt-4">/</span>
                        <span id="quantityOfProduct"
                            class=" bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-4">0</span>
                        <span id="transactionUnitName" class="text-sm text-gray-500 dark:text-gray-400 ml-2"></span>

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
        // دالة آمنة لتهيئة TomSelect بدون تكرار
        function initTomSelect(selector, options) {
            const el = document.querySelector(selector);
            if (!el) return null;
            if (el.tomselect) {
                el.tomselect.destroy();
            }
            return new TomSelect(el, options);
        }
    
        // تهيئة جميع الحقول
        const warehouseSelect              = initTomSelect('#warehouse_id', {
            create: false, placeholder: 'اختر مستودعًا', searchField: 'text'
        });
        const inventoryTransactionSelect   = initTomSelect('#inventory_transaction_item_id', {
            create: false, placeholder: 'اختر الحركة المخزنية', searchField: 'text'
        });
        const storageAreaSelect            = initTomSelect('#storage_area_id', {
            create: false, placeholder: 'اختر منطقة', searchField: 'text'
        });
        const locationSelect               = initTomSelect('#location_id', {
            create: false, placeholder: 'اختر موقعًا', searchField: 'text'
        });
    
        // حقول إضافية
        const productIdInput       = document.getElementById('product_id');
        const productionDateInput  = document.getElementById('production_date');
        const expirationDateInput  = document.getElementById('expiration_date');
        const quantityInput        = document.getElementById('quantityOfProduct');
        const unitNameSpan         = document.getElementById('productUnitName');
    
        // جلب الحركات المخزنية عند تغيير المستودع
        async function loadTransactions(warehouseId) {
            inventoryTransactionSelect.clearOptions();
            inventoryTransactionSelect.addOption({ value:'', text:'اختر الحركة المخزنية' });
            inventoryTransactionSelect.refreshOptions();
            if (!warehouseId) return;
    
            try {
                const res = await fetch(`/get-inventory-transactions/${warehouseId}`, {
                    headers: {
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (!res.ok) throw new Error();
                const data = await res.json();
                data.forEach(item => {
                    inventoryTransactionSelect.addOption({
                        value: item.id,
                        text: `${item.reference} – ${item.product_name} – ${item.sku} – ${item.barcode}`
                    });
                });
                inventoryTransactionSelect.refreshOptions();
            } catch (e) {
                console.error('Error loading transactions', e);
            }
        }
    
        // ملء حقول المنتج عند اختيار حركة
        inventoryTransactionSelect?.on('change', async id => {
            if (!id) {
                productIdInput.value = productionDateInput.value = expirationDateInput.value = '';
                quantityInput.innerText = '';
                unitNameSpan.innerText = '';
                return;
            }
            try {
                const res = await fetch(`/get-product-inventory/${id}`);
                if (!res.ok) throw new Error();
                const p = await res.json();
                productIdInput.value      = p.product_id || '';
                productionDateInput.value = p.production_date || '';
                expirationDateInput.value = p.expiration_date || '';
                quantityInput.innerText   = p.quantity || '0';
                unitNameSpan.innerText    = p.unit_name || '';
                document.getElementById('transactionUnitName').innerText = p.transaction_unit_name || '';
            } catch (e) {
                console.error('Error fetching product', e);
            }
        });
    
        // جلب المناطق التخزينية عند تغيير المستودع
        async function loadStorageAreas(warehouseId) {
            storageAreaSelect.clearOptions();
            storageAreaSelect.addOption({ value:'', text:'اختر منطقة' });
            storageAreaSelect.refreshOptions();
            if (!warehouseId) return;
    
            try {
                const res = await fetch(`/api/warehouses/${warehouseId}/storage-areas`);
                if (!res.ok) throw new Error();
                const areas = await res.json();
                areas.forEach(a => storageAreaSelect.addOption({
                    value: a.id, text: a.area_name
                }));
                storageAreaSelect.refreshOptions();
            } catch (e) {
                console.error('Error loading storage areas', e);
            }
        }
    
        // جلب المواقع عند تغيير المنطقة
        async function loadLocations(areaId) {
            locationSelect.clearOptions();
            locationSelect.addOption({ value:'', text:'اختر موقعًا' });
            locationSelect.refreshOptions();
            if (!areaId) return;
    
            try {
                const res = await fetch(`/api/storage-areas/${areaId}/locations`);
                if (!res.ok) throw new Error();
                const locs = await res.json();
                locs.forEach(l => locationSelect.addOption({
                    value: l.id, text: l.rack_code
                }));
                locationSelect.refreshOptions();
            } catch (e) {
                console.error('Error loading locations', e);
            }
        }

        // ربط الأحداث
        warehouseSelect?.on('change', id => {
            loadTransactions(id);
            loadStorageAreas(id);
        });
        storageAreaSelect?.on('change', id => {
            loadLocations(id);
        });
    
        // إذا كانت هناك قيم أولية
        @if(request('warehouse_id'))
            loadTransactions({{ request('warehouse_id') }});
            loadStorageAreas({{ request('warehouse_id') }});
        @endif
        @if(request('storage_area_id'))
            loadLocations({{ request('storage_area_id') }});
        @endif
    });
    </script>
    
