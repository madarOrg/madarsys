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

                    <!-- اختيار المستودع والحركة في بداية الإدخال -->
                    <div class="mb-4">
                        <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع" :options="$warehouses->pluck('name', 'id')" required />
                    </div>

                    <div class="mb-4">
                        <x-select-dropdown id="inventory_transaction_item_id" name="inventory_transaction_item_id" label="الحركة المخزنية" :options="[]" required />
                    </div>

                    <!-- اختيار المنتج -->
                    <div class="mb-4">
                        <x-select-dropdown id="product_id" name="product_id" label="المنتج" :options="$products->pluck('name', 'id')->toArray()" required />
                    </div>

                    <div class="mb-4">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية" required min="1" />
                    </div>

                    <!-- اختيار المنطقة التخزينية -->
                    <div class="mb-4">
                        <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية" :options="$storageAreas->pluck('area_name', 'id')" required />
                    </div>

                    <!-- إدخال الموقع الدقيق للرف -->
                    <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج" :options="$locations" required />
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
    document.addEventListener("DOMContentLoaded", function() {
        const warehouseSelect = document.getElementById("warehouse_id");
        const transactionSelect = document.getElementById("inventory_transaction_item_id");
        const productSelect = document.getElementById("product_id");
        const storageAreaSelect = document.getElementById("storage_area_id");
        const locationSelect = document.getElementById("location_id");
        const quantityDistributionDiv = document.getElementById("quantity-distribution");

        let selectedWarehouse = null;
        let selectedTransaction = null;

        // تحديث الحركات المخزنية عند اختيار المستودع
        warehouseSelect.addEventListener("change", function() {
            selectedWarehouse = this.value;
            fetchOptions(`/get-inventory-transactions/${selectedWarehouse}`, transactionSelect);
            resetSelect(productSelect);
            resetSelect(storageAreaSelect);
            resetSelect(locationSelect);
            quantityDistributionDiv.innerHTML = ''; // تفريغ توزيع الكمية
        });

        // تحديث المنتجات عند اختيار الحركة المخزنية
        transactionSelect.addEventListener("change", function() {
            selectedTransaction = this.value;
            fetchOptions(`/get-products/${selectedTransaction}`, productSelect);
        });

        // تحديث توزيع الكمية عند اختيار المنتج
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
