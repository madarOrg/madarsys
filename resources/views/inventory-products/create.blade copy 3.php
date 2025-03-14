<x-layout>
    <section>
        <div>
            <x-title :title="'إضافة منتج إلى المستودع '"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال مكان المنتج بدقة.
            </p>
            <form action="{{ route('inventory-products.store') }}" method="POST">
                @csrf

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- <!-- اختيار الفرع -->
                    <div class="mb-4">
                        <x-select-dropdown id="branch_id" name="branch_id" label="الفرع" :options="$branches->pluck('name', 'id')" required />
                    </div> --}}

                   <!-- اختيار المستودع -->
<div class="mb-4">
    <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع"
        :options="$warehouses->pluck('name', 'id')" required />
</div>

<!-- اختيار الحركة المخزنية -->
<div class="mb-4">
    <x-select-dropdown id="inventory_transaction_item_id" name="inventory_transaction_item_id"
        label="الحركة المخزنية" :options="[]" required />
</div>

<!-- اختيار المنتج -->
<div class="mb-4">
    <x-select-dropdown id="product_id" name="product_id" label="المنتج" :options="[]" required />
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
    document.addEventListener("DOMContentLoaded", function () {
        const warehouseSelect = document.getElementById("warehouse_id");
        const transactionSelect = document.getElementById("inventory_transaction_item_id");
        const productSelect = document.getElementById("product_id");

        warehouseSelect.addEventListener("change", function () {
            const warehouseId = this.value;
            transactionSelect.innerHTML = "<option value=''>جاري التحميل...</option>";

            fetch(`/api/inventory-transactions/${warehouseId}`)
                .then(response => response.json())
                .then(data => {
                    transactionSelect.innerHTML = "<option value=''>اختر الحركة المخزنية</option>";
                    for (const [id, reference] of Object.entries(data)) {
                        transactionSelect.innerHTML += `<option value="${id}">${reference}</option>`;
                    }
                })
                .catch(error => console.error("Error loading transactions:", error));
        });

        transactionSelect.addEventListener("change", function () {
            const transactionId = this.value;
            productSelect.innerHTML = "<option value=''>جاري التحميل...</option>";

            fetch(`/api/products/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    productSelect.innerHTML = "<option value=''>اختر المنتج</option>";
                    for (const [id, name] of Object.entries(data)) {
                        productSelect.innerHTML += `<option value="${id}">${name}</option>`;
                    }
                })
                .catch(error => console.error("Error loading products:", error));
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const branchSelect = document.getElementById("branch_id");
        const warehouseSelect = document.getElementById("warehouse_id");
        const transactionSelect = document.getElementById("inventory_transaction_item_id");
        const productSelect = document.getElementById("product_id");
        const storageAreaSelect = document.getElementById("storage_area_id");
        const locationSelect = document.getElementById("location_id");

        // تحديث المستودعات عند اختيار الفرع
        // branchSelect.addEventListener("change", function() {
        //     fetchOptions(`/get-warehouses/${this.value}`, warehouseSelect);
        //     resetSelect(transactionSelect);
        //     resetSelect(productSelect);
        //     resetSelect(storageAreaSelect);
        //     resetSelect(locationSelect);
        // });

        // تحديث الحركات المخزنية عند اختيار المستودع
        warehouseSelect.addEventListener("change", function() {
            fetchOptions(`/get-inventory-transactions/${this.value}`, transactionSelect);
            resetSelect(productSelect);
            resetSelect(storageAreaSelect);
            resetSelect(locationSelect);
        });

        // تحديث المنتجات عند اختيار الحركة المخزنية
        transactionSelect.addEventListener("change", function() {
            fetchOptions(`/get-products/${this.value}`, productSelect);
        });
        // تحديث المناطق التخزينية عند اختيار المستودع
        warehouseSelect.addEventListener("change", function() {
            const warehouseId = this.value;
            storageAreaSelect.innerHTML = '<option value="">اختر منطقة تخزينية</option>';
            locationSelect.innerHTML = '<option value="">اختر موقعًا</option>';
            if (warehouseId) {
                fetch(`/get-storage-areas/${warehouseId}`)
                    .then(response => response.json())
                    .then(data => {
                        Object.entries(data).forEach(([id, name]) => {
                            let option = new Option(name, id);
                            storageAreaSelect.add(option);
                        });
                    })
                    .catch(error => console.error('Error fetching storage areas:', error));
            }
        });

        // تحديث المواقع عند اختيار المنطقة التخزينية
        storageAreaSelect.addEventListener("change", function() {
            const storageAreaId = this.value;
            locationSelect.innerHTML = '<option value="">اختر موقعًا</option>';

            if (storageAreaId) {
                fetch(`/get-locations/${storageAreaId}`)
                    .then(response => response.json())
                    .then(data => {
                        Object.entries(data).forEach(([id, name]) => {
                            let option = new Option(name, id);
                            locationSelect.add(option);
                        });
                    })
                    .catch(error => console.error('Error fetching locations:', error));
            }
        });

        // دالة جلب البيانات وتعبئة القائمة
        function fetchOptions(url, selectElement) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    resetSelect(selectElement);
                    Object.entries(data).forEach(([id, name]) => {
                        selectElement.add(new Option(name, id));
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // دالة لتفريغ القائمة
        function resetSelect(selectElement) {
            selectElement.innerHTML = '<option value=""> اختر</option>';
        }
    });
</script>

<script>
    // تحديث التوزيع عند اختيار المنتج
    productSelect.addEventListener("change", function() {
        const productId = this.value;

        if (productId) {
            fetch(`/get-quantity-distribution/${productId}`)
                .then(response => response.json())
                .then(data => {
                    quantityDistributionDiv.innerHTML = '';
                    data.storage_areas.forEach(area => {
                        const areaDiv = document.createElement('div');
                        areaDiv.classList.add('mb-4');
                        areaDiv.innerHTML = `
                            <label for="area_${area.id}" class="block text-sm font-medium text-gray-700">منطقة: ${area.name}</label>
                            <input type="number" id="area_${area.id}" name="storage_areas[${area.id}]" class="mt-1 block w-full" min="0" max="${data.available_quantity}" value="0">
                        `;
                        quantityDistributionDiv.appendChild(areaDiv);
                    });
                })
                .catch(error => console.error('Error fetching quantity distribution:', error));
        }
    });
</script>
