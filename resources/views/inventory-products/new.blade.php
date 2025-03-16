<x-layout>
    <section>
        <div>
            <x-title :title="'إضافة منتج إلى المستودع '"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال مكان المنتج بدقة.
            </p>
            <form action="{{ route('inventory-products.store') }}" method="POST">
                @csrf

                <!-- المستودع -->
                <div class="mb-4">
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">المستودع</label>
                    <input type="text" id="warehouse_id" value="{{ $product->warehouse->name }}" class="form-control" readonly />
                    <input type="hidden" name="warehouse_id" value="{{ $product->warehouse->id }}">
                </div>

                <!-- الحركة المخزنية -->
                <div class="mb-4">
                    <label for="inventory_transaction_item_id" class="block text-sm font-medium text-gray-700">الحركة المخزنية</label>
                    <input type="text" id="inventory_transaction_item_id" value="{{ $transactionItem->inventoryTransaction->reference }}" class="form-control" readonly />
                    <input type="hidden" name="inventory_transaction_item_id" value="{{ $transactionItem->id }}">
                </div>

                <!-- المنتج -->
                <div class="mb-4">
                    <label for="product_id" class="block text-sm font-medium text-gray-700">المنتج</label>
                    <input type="text" id="product_id" value="{{ $product->product->name }}" class="form-control" readonly />
                    <input type="hidden" name="product_id" value="{{ $product->product->id }}">
                </div>

                <!-- تاريخ الإنتاج -->
                <div class="mb-4">
                    <x-file-input type="date" id="production_date" name="production_date" label="تاريخ الإنتاج" required />
                </div>

                <!-- تاريخ الانتهاء -->
                <div class="mb-4">
                    <x-file-input type="date" id="expiration_date" name="expiration_date" label="تاريخ الانتهاء" required />
                </div>

                <!-- الكمية -->
                <div class="mb-4">
                    <x-file-input type="number" id="quantity" name="quantity" label="الكمية" required min="1" />
                </div>

                <!-- المنطقة التخزينية -->
                <div class="mb-4">
                    <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية"
                        :options="$storageAreas->pluck('area_name', 'id')" required />
                </div>

                <!-- الموقع -->
                <div class="mb-4">
                    <x-select-dropdown id="location_id" name="location_id" label="الموقع"
                        :options="$locations->pluck('rack_code', 'id')" required />
                </div>

                <!-- زر الحفظ -->
                <div class="sm:col-span-6 flex justify-end mt-6">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
