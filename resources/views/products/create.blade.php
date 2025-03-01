<x-layout>
    <section class="">
        <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-900 rounded-lg shadow-md">
            <x-title :title="'إضافة منتج جديد'"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى إدخال بيانات المنتج بدقة لضمان تنظيم المخزون.
            </p>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="col-span-1">
                    <x-select-dropdown
                        id="warehouse_id"
                        name="warehouse_id"
                        label="المستودع"
                        {{-- :options="$WarehouseLocations->pluck('name', 'id')" 
                        :selected="old('warehouse_id')"  --}}
                        required
                    />
                </div>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                    
                    <div class="col-span-1">
                        <x-file-input id="name" name="name" label="اسم المنتج" type="text" required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="barcode" name="barcode" label="الباركود" type="text" required />
                    </div>
                    <div class="col-span-1">
                        <x-select-dropdown id="category" name="category" label="فئة المنتج" :options="$categories->pluck('name', 'id')"
                            :selected="old('category', $selectedCategoryId ?? null)" required />
                    </div>


                    <div class="col-span-1">
                        <x-file-input id="brand" name="brand" label=" اسم العلامة التجارية" type="text" />
                    </div>

                    <div class="col-span-1">
                        <x-file-input id="stockQuantity" name="stockQuantity" label="الكمية المتوفرة" type="number"
                            required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="unit" name="unit" label="الوحدة" type="text" required />
                    </div>
                    <div class="col-span-1">
                        <x-select-dropdown
                            id="WarehouseLocations"
                            name="WarehouseLocations"
                            label="المخزن"
                            {{-- :options="$WarehouseLocations->pluck('name', 'id')"
                            :selected="old('WarehouseLocations', $selectedStorageLocationId ?? null)" --}}
                            required
                        />
                    </div>
                    
                    <div class="col-span-1">
                        <x-file-input id="reorderLevel" name="reorderLevel" label="حد الطلب الأدنى" type="number"
                            required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="purchasePrice" name="purchasePrice" label="سعر الشراء" type="number"
                            required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="sellingPrice" name="sellingPrice" label="سعر البيع" type="number" required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="tax" name="tax" label="الضريبة (%)" type="number" />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="discount" name="discount" label="التخفيضات (%)" type="number" />
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                    <div class="col-span-1">
                        <x-file-input id="supplierName" name="supplierName" label="اسم المورد" type="text"
                            required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="supplierContact" name="supplierContact" label="رقم المورد" type="text"
                            required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="purchaseDate" name="purchaseDate" label="تاريخ الشراء" type="date"
                            required />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="manufacturingDate" name="manufacturingDate" label="تاريخ التصنيع"
                            type="date" />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="expirationDate" name="expirationDate" label="تاريخ إنتهاء المنتج" type="date" />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="lastUpdated" name="lastUpdated" label="آخر تحديث" type="date" />
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="logo" name="logo" type="file" label="صورة  المنتج" />
                        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                            تم اختيار الملف: {{ session('file_uploaded') }}
                        </p>
                    </div>
                    <div class="col-span-1">
                        <x-file-input id="Attachments" name="Attachments" type="file" label="مرفقات  إضافية" />
                        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                            تم اختيار الملف: {{ session('file_uploaded') }}
                        </p>
                    </div>
                    <div class="col-span-1">
                        <input type="checkbox" id={id} name={name} checked={is_active} />
                        <label For ={id}>
                            فعال
                        </label>
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
