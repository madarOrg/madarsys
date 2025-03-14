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
    {{-- <x-select name="inventory_transaction_item_id" :options="$products" /> --}}
    <x-select name="inventory_transaction_item_id" :options="[$products]" :route="`{{ url('/api/search/items') }}`" />

    {{-- <x-select-dropdown id="inventory_transaction_item_id" name="inventory_transaction_item_id" label="الحركة المخزنية" :options="[]" required /> --}}
</div>

<!-- اختيار المنتج -->
<div class="mb-4">
    <x-select name="product_id" :options="[$products]" :route="url('/api/search/products')" />
    {{-- <x-select name="product_id" :options="$products" /> --}}

    {{-- <x-select-dropdown id="product_id" name="product_id" label="المنتج" :options="[]" required /> --}}
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

