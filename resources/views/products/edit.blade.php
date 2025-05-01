<x-layout>
    <section class="">
        <div class="">
            <x-title :title="'تعديل منتج - ' . $product->name"></x-title>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يمكنك تعديل بيانات المنتج وحفظ التغييرات.
            </p>
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
               
                 

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- عمود الصورة مع المعاينة -->
                    <div class="lg:col-span-1 flex flex-col items-center justify-center">
                        <div class="w-full">
                            <label for="image" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">صورة المنتج</label>
                            <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                                class="block w-full text-sm text-gray-500 file:py-2 file:px-4 file:border file:rounded-md file:border-gray-300 dark:file:bg-gray-700 dark:text-gray-300" />
                        </div>
                        <div class="mt-4">
                            <!-- عرض الصورة القديمة إذا كانت موجودة -->
                            @if ($product->image)
                                <img id="imagePreview" class="w-full rounded-md object-contain border border-gray-300"
                                    src="{{ asset('storage/'.$product->image) }}" alt="معاينة الصورة" />
                            @else
                                <img id="imagePreview" class="w-full rounded-md object-contain border border-gray-300"
                                    src="" alt="معاينة الصورة" style="display: none;" />
                            @endif
                        </div>
                   
                    </div>
                    <div class="lg:col-span-2 grid grid-cols-1 gap-8 mt-10">
                        <!-- الحقول -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 border border-gray-300 p-4 rounded-md ">
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
                        <x-file-input id="stock_quantity" name="stock_quantity" label="بداية المدة" type="number"
                            value="{{ old('stock_quantity', $product->stock_quantity) }}" required />
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
                        <x-file-input id="min_stock_level" name="min_stock_level" label="حد الطلب الأدنى" type="number"
                            value="{{ old('min_stock_level', $product->min_stock_level) }}" required />
                    </div>
                    <div class="">
                        <x-file-input id="max_stock_level" name="max_stock_level" label="حد الطلب الأعلى" type="number"
                            value="{{ old('max_stock_level', $product->max_stock_level) }}" required />
                    </div>
                </div>

                   <!-- العمود الثاني: الاختيارات -->
                   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 border border-gray-300 p-4 rounded-md">
                    <div>
                        <label for="category_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">فئة المنتج</label>
                        <select id="category_id" name="category_id" class="tom-select w-full mt-1" required>
                            <option value="">اختر فئة</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="unit_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">الوحدة</label>
                        <select id="unit_id" name="unit_id" class="tom-select w-full mt-1" required>
                            <option value="">اختر وحدة</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" 
                                    {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="supplier_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">المورد</label>
                        <select id="supplier_id" name="supplier_id" class="tom-select w-full mt-1">
                            <option value="">اختر المورد</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="">
                        <label for="brand_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">العلامة التجارية</label>
                        <select id="brand_id" name="brand_id" class="tom-select w-full mt-1">
                            <option value="">اختر العلامة</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="manufacturing_country_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">بلد الصنع</label>
                        <select id="manufacturing_country_id" name="manufacturing_country_id" class="tom-select w-full mt-1">
                            <option value="">اختر بلد</option>
                            @foreach ($manufacturingCountries as $country)
                                <option value="{{ $country->id }}" 
                                    {{ old('manufacturing_country_id', $product->manufacturing_country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5 border border-gray-300 p-4 rounded-md">

                   
                    {{-- <div class="">
                        <x-file-input id="manufacturing_date" name="manufacturing_date" label="تاريخ الإنتاج"
                            type="date" value="{{ old('manufacturingDate', $product->manufacturing_date) }}" />
                    </div>
                    <div class="">
                        <x-file-input id="expiration_date" name="expiration_date" label="تاريخ إنتهاء المنتج"
                            type="date" value="{{ old('expirationDate', $product->expiration_date) }}" />
                    </div> --}}
                    <x-textarea id="ingredients" name="ingredients" label="المكونات" :value="old('ingredients', $product->ingredients)" />
                        <x-textarea id="description" name="description" label="الوصف" :value="old('description', $product->description)" />
                        <x-textarea id="notes" name="notes" label="ملاحظات إضافية" :value="old('notes', $product->notes)" />
                            <x-file-input 
                            id="purchase_date" 
                            name="purchase_date" 
                            label="تاريخ الشراء" 
                            type="date" 
                            value="{{ old('purchase_date', $product->purchase_date ? \Carbon\Carbon::parse($product->purchase_date)->format('Y-m-d') : '') }}" 
                            required 
                        />
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
                        <label for="is_active" class="ml-2">فعال</label>
                    </div>                   
                </div>

                <div class="mt-4 mb-4 flex justify-end">
                    <x-button type="submit">تحديث </x-button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block'; // لإظهار الصورة عند معاينتها
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>