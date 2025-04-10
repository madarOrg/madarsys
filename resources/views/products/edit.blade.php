<x-layout>
    <section class="">
        <div class="">
            <x-title :title="'تعديل منتج - ' . $product->id"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يمكنك تعديل بيانات المنتج وحفظ التغييرات.
            </p>
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
               
                 

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 min-h-full">
                    
                    <div class="">
                        <x-file-input id="name" name="name" label="اسم المنتج" type="text" value="{{ old('name', $product->name) }}" required />
                    </div>
                    
                    <div class="">
                        <x-file-input id="barcode" name="barcode" label="الباركود" type="text" value="{{ old('barcode', $product->barcode) }}" required />
                    </div>
                    
                    <div class="">
                        <x-file-input id="sku" name="sku" label="كود التخزين SKU" type="text"
                            :value="old('sku', $product->sku)" />
                    </div>
                    

                    <div class="">
                        <x-select-dropdown id="category_id" name="category_id" label="فئة المنتج" 
                            :options="$categories->pluck('name', 'id')"
                            :selected="old('category_id', $product->category_id)" required />
                    </div>

                    <div class="">
                        <x-file-input id="brand" name="brand" label=" اسم العلامة التجارية" type="text" value="{{ old('brand', $product->brand) }}" />
                    </div>

                    <div class="">
                        <x-file-input id="stock_quantity" name="stock_quantity" label="الكمية المتوفرة" type="number"
                            value="{{ old('stock_quantity', $product->stock_quantity) }}" required />
                    </div>

                    <div class="">
                        <x-select-dropdown
                            name="unit_id" 
                            id="unit_id" 
                            label="الوحدة" 
                            :options="$units->pluck('name', 'id')" 
                            :selected="old('unit_id', $product->unit_id ?? null)" 
                        />
                    </div>
                    
                    <div class="">
                        <x-file-input id="min_stock_level" name="min_stock_level" label="حد الطلب الأدنى" type="number"
                            value="{{ old('min_stock_level', $product->min_stock_level) }}" required />
                    </div>
                    <div class="">
                        <x-file-input id="max_stock_level" name="max_stock_level" label="حد الطلب الأعلى" type="number"
                            value="{{ old('max_stock_level', $product->max_stock_level) }}" required />
                    </div>

                    <div class="">
                        <x-file-input id="purchase_price" name="purchase_price" label="سعر الشراء" type="number"
                            value="{{ old('purchase_price', $product->purchase_price) }}" required />
                    </div>

                    <div class="">
                        <x-file-input id="selling_price" name="selling_price" label="سعر البيع" type="number"
                            value="{{ old('selling_price', $product->selling_price) }}" required />
                    </div>

                    <div class="">
                        <x-file-input id="tax" name="tax" label="الضريبة (%)" type="number"
                            value="{{ old('tax', $product->tax) }}" />
                    </div>

                    <div class="">
                        <x-file-input id="discount" name="discount" label="التخفيضات (%)" type="number"
                            value="{{ old('discount', $product->discount) }}" />
                    </div>

                    <div class="">
                        <x-select-dropdown id="supplier_id" name="supplier_id" label="المورد" 
                            :options="$suppliers->pluck('name', 'id')->toArray()"
                            :selected="old('supplier_id', $product->supplier_id)" />
                    </div>

                    <div class="">
                        <x-file-input id="supplier_contact" name="supplier_contact" label="رقم المورد" type="text"
                            value="{{ old('supplierContact', $product->supplier_contact) }}" required />
                    </div>

                    <x-file-input 
                    id="purchase_date" 
                    name="purchase_date" 
                    label="تاريخ الشراء" 
                    type="date" 
                    value="{{ old('purchase_date', $product->purchase_date ? \Carbon\Carbon::parse($product->purchase_date)->format('Y-m-d') : '') }}" 
                    required 
                />
                

                    <div class="">
                        <x-file-input id="manufacturing_date" name="manufacturing_date" label="تاريخ التصنيع"
                            type="date" value="{{ old('manufacturingDate', $product->manufacturing_date) }}" />
                    </div>

                    <div class="">
                        <x-file-input id="expiration_date" name="expiration_date" label="تاريخ إنتهاء المنتج"
                            type="date" value="{{ old('expirationDate', $product->expiration_date) }}" />
                    </div>

                    <div class="">
                        <x-file-input id="image" name="image" type="file" label="صورة المنتج" />
                        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                            الصورة الحالية: <a href="{{ asset($product->image) }}" target="_blank" class="text-blue-500">عرض</a>
                        </p>
                    </div>
{{-- 
                    <div class="">
                        <x-file-input id="Attachments" name="Attachments" type="file" label="مرفقات إضافية" />
                        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                            المرفقات الحالية: <a href="{{ asset($product->attachments) }}" target="_blank" class="text-blue-500">عرض</a>
                        </p>
                    </div>
                    <div class="">
                        <x-textarea id="notes" name="notes" label="ملاحظات" type="textarea" 
                            value="{{ old('notes', $product->notes) }}" />
                    </div> --}}
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
                        <label for="is_active" class="ml-2">فعال</label>
                    </div>
                    
                    
                </div>

                <div class="sm:col-span-6 flex justify-end mt-6">
                    <x-button type="submit">تحديث </x-button>
                </div>

            </form>
        </div>
    </section>
</x-layout>
