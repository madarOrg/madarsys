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
                    <input type="hidden" name="distribution_type" value="{{ request('distribution_type', '1') }}">

                <div class="mb-4">
                    <x-file-input label="المستودع" id="warehouse_id" value="{{ $product->warehouse->name }}" readonly
                        :model="null" name="warehouse_id" type="text" />
                    <input type="hidden" name="warehouse_id" value="{{ $product->warehouse->id }}">
                </div>

                    <!-- الحركة المخزنية -->
                    <div class="mb-4">
                        <x-file-input label="الحركة المخزنية" id="inventory_transaction_item_id"
                            value="{{ $transactionItem->inventoryTransaction->reference }}" readonly :model="null"
                            name="inventory_transaction_item_id" type="text" />
                        <input type="hidden" name="inventory_transaction_item_id" value="{{ $transactionItem->id }}">
                    </div>


                    <!-- المنتج -->
                    <div class="mb-4">
                        <x-file-input label="المنتج" id="product_id" value="{{ $product->product->name }}" readonly
                            :model="null" name="product_id" type="text" />
                        <input type="hidden" name="product_id" value="{{ $product->product->id }}">
                    </div>


                    <!-- تاريخ الإنتاج -->
                    <div class="mb-4">
                        <x-file-input type="date" id="production_date" name="production_date" label="تاريخ الإنتاج"
                             />
                            <input type="hidden" name="production_date" value="{{ $transactionItem->production_date }}">

                    </div>

                    <!-- تاريخ الانتهاء -->
                    <div class="mb-4">
                        <x-file-input type="date" id="expiration_date" name="expiration_date" label="تاريخ الانتهاء"
                             />
                            <input type="hidden" name="expiration_date" value="{{ $transactionItem->expiration_date }}">

                    </div>

                    <!-- الكمية -->
                    <div class="mb-4">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية" required
                            min="1" />
                    </div>

                    <!-- المنطقة التخزينية -->
                    <div class="mb-4">
                        <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية"
                            :options="$storageAreas->pluck('area_name', 'id')" required />
                    </div>

                    <!-- الموقع -->
                    {{-- <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="الموقع" :options="$locations->pluck('rack_code', 'id')"
                            required />
                    </div> --}}
                    <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج" :options="$locations"
                            required />
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