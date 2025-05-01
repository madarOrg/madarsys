<x-layout>
    <div class=" min-h-screen py-5 px-4">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="">
                <!-- عنوان الصفحة -->
                <x-title :title="' إضافة منتج جديد'"></x-title>
                <p class="text-sm text-gray-500 dark:text-gray-400 m-8">
                    أدخل تفاصيل المنتج بدقة لتسهيل إدارة المخزون.
                </p>

                <!-- تصميم الكارت بصورة معاينة فورية -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- عمود الصورة مع المعاينة -->
                    <div class="lg:col-span-1 flex flex-col items-center justify-center">
                        <div class="w-full">
                            <label for="image" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">صورة
                                المنتج</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                onchange="previewImage(event)"
                                class="block w-full text-sm text-gray-500 file:py-2 file:px-4 file:border file:rounded-md file:border-gray-300 dark:file:bg-gray-700 dark:text-gray-300" />
                        </div>
                        <div class="mt-4">
                            <img id="imagePreview"
                                class="w-full  rounded-md object-contain border border-gray-300"
                                src="" alt="معاينة الصورة" style="display: none;">
                        </div>
                    </div>

                    
                    <div class="lg:col-span-2 grid grid-cols-1 gap-8 mt-10">
                        <!-- الحقول -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 border border-gray-300 p-4 rounded-md ">
                            <!-- العمود الأول: تفاصيل المنتج -->
                            <x-file-input id="name" name="name" label="اسم المنتج" type="text" required />
                            <x-file-input id="barcode" name="barcode" label="الباركود" type="text" required />

                            <input type="hidden" name="sku" value="{{ $generatedSku ?? '' }}">
                            <div class="">
                                <x-file-input id="sku" name="sku" label="كود التخزين SKU" type="text" :value="old('sku', 'SKU-' . Str::random(8))" readonly />
                            </div>
                            
                            <x-file-input id="stock_quantity" name="stock_quantity" label=" بداية المدة"
                                type="number" required />
                            <x-file-input id="purchase_price" name="purchase_price" label="سعر الشراء" type="number"
                                required />
                            <x-file-input id="selling_price" name="selling_price" label="سعر البيع" type="number"
                                required />
                            <x-file-input id="tax" name="tax" label="الضريبة (%)" type="number" />
                            <x-file-input id="discount" name="discount" label="التخفيض (%)" type="number" />
                            <x-file-input id="min_stock_level" name="min_stock_level" label="الحد الأدنى للمخزون"
                                type="number" />
                            <x-file-input id="max_stock_level" name="max_stock_level" label="الحد الأقصى للمخزون"
                                type="number" />
                        </div>

                        <!-- العمود الثاني: الاختيارات -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 border border-gray-300 p-4 rounded-md">
                            <div>
                                <label for="category_id"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300">فئة المنتج</label>
                                <select id="category_id" name="category_id" class="tom-select w-full mt-1" required>
                                    <option value="">اختر فئة</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="unit_id"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300">الوحدة</label>
                                <select id="unit_id" name="unit_id" class="tom-select w-full mt-1" required>
                                    <option value="">اختر وحدة</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="supplier_id"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300">المورد</label>
                                <select id="supplier_id" name="supplier_id" class="tom-select w-full mt-1">
                                    <option value="">اختر المورد</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="brand_id"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300">العلامة
                                    التجارية</label>
                                <select id="brand_id" name="brand_id" class="tom-select w-full mt-1">
                                    <option value="">اختر العلامة</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="manufacturing_country_id"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300">بلد الصنع</label>
                                <select id="manufacturing_country_id" name="manufacturing_country_id"
                                    class="tom-select w-full mt-1">
                                    <option value="">اختر بلد</option>
                                    @foreach ($manufacturingCountries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('manufacturing_country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        
            <!-- تواريخ المنتج -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5 border border-gray-300 p-4 rounded-md">
               
                {{-- <x-file-input id="manufacturing_date" name="manufacturing_date" label="تاريخ الإنتاج"
                    type="date" />
                <x-file-input id="expiration_date" name="expiration_date" label="تاريخ الانتهاء" type="date" />
            --}}

              <!-- الملاحظات والمكونات -->
            
                <x-textarea id="ingredients" name="ingredients" label="المكونات" :value="old('ingredients')" />
                <x-textarea id="description" name="description" label=" الوصف" :value="old('notes')" />
                <x-textarea id="notes" name="notes" label="ملاحظات إضافية" :value="old('notes')" />
                <x-file-input id="purchase_date" name="purchase_date" label="تاريخ الشراء" type="date"
                required />
               <!-- الحالة -->
              <div class="mt-6 flex items-center space-x-2 rtl:space-x-reverse">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active') ? 'checked' : '' }} />
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">المنتج
                    نشط</label>
            </div>

            </div>

           
            <!-- زر الحفظ -->
            <div class="mt-4 mb-4 flex justify-end">
                <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white">
                     حفظ 
                </x-button>
            </div>
            </div>
        </form>
    </div>
</x-layout>
    <!-- سكريبت جافا سكريبت للمعاينة الفورية للصورة -->
    <script>
        function previewImage(event) {
            var output = document.getElementById('imagePreview');
            if (event.target.files && event.target.files[0]) {
                output.src = URL.createObjectURL(event.target.files[0]);
                output.style.display = 'block';
                output.onload = function() {
                    URL.revokeObjectURL(output.src); // تحرير الذاكرة
                }
            } else {
                output.src = '';
                output.style.display = 'none';
            }
        }
    </script>

