@php
    use Carbon\Carbon;
    $product = count(old()) ? (object) old() : $product;
    // var_dump($oldProduct);
@endphp

<x-layout>
    <section>
        <div>
            <x-title :title="'تحديث منتج في المستودع'"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى تحديث الكمية والتواريخ و مواقع التخزين فقط.
            </p>

            <form action="{{ route('inventory-products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">

                    <div class="mb-4">
                        <x-file-input type="text" id="batch_number" name="batch_number" label="رقم الدفعة"
                            value="{{ old('batch_number', $product->batch_number) }}" readonly />
                    </div>
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="branch_id" value="{{ $product->branch_id }}">
                    <input type="hidden" name="warehouse_id" value="{{ $product->warehouse_id }}">
                    <input type="hidden" name="inventory_transaction_item_id"
                        value="{{ $product->inventory_transaction_item_id }}">

                    <!-- المستودع -->
                    <div class="mb-4">
                        <label for="warehouse_id" class=" block text-sm font-medium text-gray-700">المستودع</label>
                        <input type="text" id="warehouse_id" value="{{ $product->warehouse->name }}"
                            class="form-control w-full" readonly />
                    </div>

                    <!-- الحركة المخزنية -->
                    <div class="mb-4">
                        <label for="inventory_transaction_item_id"
                            class="block text-sm font-medium text-gray-700">الحركة المخزنية</label>
                        <input type="text" id="inventory_transaction_item_id"
                            value="{{ $product->transactionItem->inventoryTransaction->reference }}"
                            class="form-control w-full" readonly />
                    </div>

                    <!-- تاريخ الإنتاج -->
                    <div class="mb-4">
                        <x-file-input type="date" id="production_date" name="production_date" label="تاريخ الإنتاج"
                            value="{{ old('production_date', $product->production_date ? Carbon::parse($product->production_date)->format('Y-m-d') : '') }}" />
                    </div>

                    <!-- تاريخ الانتهاء -->
                    <div class="mb-4">
                        <x-file-input type="date" id="expiration_date" name="expiration_date" label="تاريخ الانتهاء"
                            value="{{ old('expiration_date', $product->expiration_date ? Carbon::parse($product->expiration_date)->format('Y-m-d') : '') }}"
                            required />
                    </div>

                    <!-- الكمية -->
                    <div class="mb-4">
                        <x-file-input type="number" id="quantity" name="quantity" label="الكمية"
                            value="{{ old('quantity', $product->quantity) }}" required min="1" />
                    </div>

                    <!-- المنطقة التخزينية -->
                    <div class="mb-4">
                        <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية"
                        :options="$storageAreas->pluck('area_name', 'id')" :selected="old('storage_area_id', $product->storage_area_id)" required />
                    
                    </div>

                    <!-- موقع المنتج -->
                    <div class="mb-4">
                        <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج"
                        :options="$locations->pluck('rack_code', 'id')" :selected="old('location_id', $product->location_id)" required />
                    
                    </div>


                </div>

                <div class="sm:col-span-6 flex justify-end mt-6">
                    <x-button type="submit">تحديث</x-button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
