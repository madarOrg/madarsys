<x-layout>
    <section class="">
        <div class="">
            <x-title :title="'إضافة منتج جديد'"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال بيانات المنتج بدقة لضمان تنظيم المخزون.
            </p>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div
                    class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 min-h-full">
                    {{-- <div class="">
                        <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع"
                         :options="$WarehouseLocations->pluck('name', 'id')" 
                            :selected="old('warehouse_id')" 
                            required />
                    </div> --}}
                    <div class="">
                        <x-file-input id="name" name="name" label="اسم المنتج" type="text" required />
                    </div>
                    <div class="">
                        <x-file-input id="barcode" name="barcode" label="الباركود" type="text" required />
                    </div>
                    {{-- <div class="">
                        <x-file-input id="sku" name="sku" label=" كود التخزين SKU ( ينشئ تلقائي)" type="text"
                            :value="old('sku', $generatedSku ?? '')" readonly />
                    </div> --}}
                    <input type="hidden" name="sku" value="{{ $generatedSku ?? '' }}">

                    <div class="">
                        <x-select-dropdown id="category_id" name="category_id" label="فئة المنتج" :options="$categories->pluck('name', 'id')"
                            :selected="old('category_id', $selectedCategoryId ?? null)" required />
                    </div>

                    <div class="">
                        <x-file-input id="brand" name="brand" label=" اسم العلامة التجارية" type="text" />
                    </div>

                    <div class="">
                        <x-file-input id="stock_quantity" name="stock_quantity" label="الكمية المتوفرة" type="number"
                            required />
                    </div>
                    <div class="">
                        <x-select-dropdown 
                            name="unit_id" 
                            id="unit_id" 
                            label="الوحدة" 
                            :options="$units->pluck('name', 'id')" 
                            :selected="isset($product) ? $product->unit_id : null" 
                        />
                    </div>
                    
                        {{-- <div>
                            <label for="unit_id">الوحدة</label>
                            <select id="unit_id" name="unit_id" required>
                                <option value="">اختر</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @if (isset($product) && $product->unit_id == $unit->id) selected @endif>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                    

                    {{-- <div class="">
                        <x-select-dropdown id="WarehouseLocations" name="WarehouseLocations" label="المخزن"
                            :options="$WarehouseLocations->pluck('name', 'id')"
                            :selected="old('WarehouseLocations', $selectedStorageLocationId ?? null)" required />
                    </div> --}}

                    <div class="">
                        <x-file-input id="min_stock_level" name="min_stock_level" label="حد الطلب الأدنى" type="number"
                            required />
                    </div>
                    <div class="">
                        <x-file-input id="max_stock_level" name="max_stock_level" label="حد الطلب الأعلى" type="number"
                            required />
                    </div>
                    <div class="">
                        <x-file-input id="purchase_price" name="purchase_price" label="سعر الشراء" type="number"
                            required />
                    </div>
                    <div class="">
                        <x-file-input id="selling_price" name="selling_price" label="سعر البيع" type="number"
                            required />
                    </div>
                    <div class="">
                        <x-file-input id="tax" name="tax" label="الضريبة (%)" type="number" />
                    </div>
                    <div class="">
                        <x-file-input id="discount" name="discount" label="التخفيضات (%)" type="number" />
                    </div>
                    <div class="">
                        <x-select-dropdown id="supplier_id" name="supplier_id" label="المورد" :options="$suppliers->pluck('name', 'id')->toArray()"
                            :selected="old('supplier_id')" />

                    </div>
                    <div class="">
                        <x-file-input id="supplier_contact" name="supplier_contact" label="رقم المورد" type="text"
                            required />
                    </div>
                    <div class="">
                        <x-file-input id="purchase_date" name="purchase_date" label="تاريخ الشراء" type="date"
                            required />
                    </div>
                    <div class="">
                        <x-file-input id="manufacturing_date" name="manufacturing_date" label="تاريخ التصنيع"
                            type="date" />
                    </div>
                    <div class="">
                        <x-file-input id="expiration_date" name="expiration_date" label="تاريخ إنتهاء المنتج"
                            type="date" />
                    </div>
                    {{-- <div class="">
                        <x-file-input id="last_updated" name="last_updated" label="آخر تحديث" type="date" />
                    </div> --}}
                    <div class="">
                        <x-file-input id="image	" name="image" type="file" label="صورة  المنتج" />
                        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                            تم اختيار الملف: {{ session('file_uploaded') }}
                        </p>
                    </div>
                    <div class="">
                        <x-file-input id="Attachments" name="Attachments" type="file" label="مرفقات  إضافية" />
                        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                            تم اختيار الملف: {{ session('file_uploaded') }}
                        </p>
                    </div>
                    <div class="">
                        <x-textarea id="notes" name="notes" label="ملاحظات" type="textarea" />
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2">فعال</label>
                    </div>
                   
                    
                    </div>
                </div>


        </div>



        <div class="sm:col-span-6 flex justify-end mt-6">
            <x-button type="submit">حفظ </x-button>
        </div>
        </form>
        </div>

        </form>
    </section>
</x-layout>
