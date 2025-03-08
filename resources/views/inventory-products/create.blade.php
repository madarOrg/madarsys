<x-layout>
    <section>
        <div>
            <x-title :title="'إضافة منتج إلى المستودع '"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال  مكان المنتج  بدقة.
            </p>
            <form action="{{ route('inventory-products.store') }}" method="POST">
                @csrf

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- اختيار المنتج -->
                    <div class="mb-4">
                        <x-select-dropdown id="product_id" name="product_id" label="المنتج"
                            :options="$products->pluck('name', 'id')" required />
                    </div>

                    <!-- اختيار الفرع -->
                    <div class="mb-4">
                        <x-select-dropdown id="branch_id" name="branch_id" label="الفرع" 
                            :options="$branches->pluck('name', 'id')" />
                    </div>

                    <!-- اختيار المستودع -->
                    <div class="mb-4">
                        <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع" 
                            :options="$warehouses->pluck('name', 'id')" required />
                    </div>

                    <!-- اختيار المنطقة التخزينية -->
                    <div class="mb-4">
                        <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية" 
                            :options="$storageAreas->pluck('area_name', 'id')" required />
                    </div>

                    <!-- إدخال الموقع الدقيق للرف -->
                    <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج" 
                        :options="$locations" required />
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
    const branchSelect = document.getElementById("branch_id");
    const warehouseSelect = document.getElementById("warehouse_id");
    const storageAreaSelect = document.getElementById("storage_area_id");
    const locationSelect = document.getElementById("location_id");

    // تحديث قائمة المستودعات عند اختيار الفرع
    branchSelect.addEventListener("change", function () {
        const branchId = this.value;
        warehouseSelect.innerHTML = '<option value="">اختر مستودعًا</option>';
        storageAreaSelect.innerHTML = '<option value="">اختر منطقة تخزينية</option>';
        locationSelect.innerHTML = '<option value="">اختر موقعًا</option>';

        if (branchId) {
            fetch(`/get-warehouses/${branchId}`)
                .then(response => response.json())
                .then(data => {
                    Object.entries(data).forEach(([id, name]) => {
                        let option = new Option(name, id);
                        warehouseSelect.add(option);
                    });
                })
                .catch(error => console.error('Error fetching warehouses:', error));
        }
    });

    // تحديث قائمة المناطق التخزينية عند اختيار المستودع
    warehouseSelect.addEventListener("change", function () {
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

    // تحديث قائمة المواقع عند اختيار المنطقة التخزينية
    storageAreaSelect.addEventListener("change", function () {
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
});
</script>
