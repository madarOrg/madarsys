<x-layout>
    <section>
        <div>
            <x-title :title="'إضافة منتج لى المستودع '"></x-title>
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
                        <x-file-input id="shelf_location" name="shelf_location" label="رقم الرف أو الموقع" type="text" />
                    </div>

                </div>

                <div class="sm:col-span-6 flex justify-end mt-6">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
