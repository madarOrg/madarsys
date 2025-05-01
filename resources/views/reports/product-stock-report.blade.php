<x-layout>
    <div class="container">

        <!-- زر الطباعة -->
        <div class="hide-on-print text-right mt-2 mb-4">
            <button onclick="window.print()"
                class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 dark:text-gray-400 text-base font-semibold leading-7">
                طباعة التقرير
            </button>
        </div>

        <!-- رأس التقرير -->
        <x-reportHeader>
            <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300">
                تقرير المنتجات بالتفاصيل الكاملة
            </h1>
        </x-reportHeader>

        <!-- زر إظهار/إخفاء الفلاتر -->
        <div x-data="{ open: true }" class=" mb-4">
            <button type="button" @click="open = !open"
                class=" hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>

            <!-- نموذج الفلترة -->
            <div x-show="open" x-transition>
                <form action="{{ route('reports.product-stock') }}" method="GET" class=" p-4 rounded shadow">
                    <div class=" grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- البحث باسم المنتج / SKU / الباركود -->
                        <div>
                            <label for="products" class="block mb-1">اسم المنتج / الباركود / SKU</label>
                            <select name="products[]" class="w-full tom-select" multiple>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ collect(request('products'))->contains($product->id) ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->barcode }} - {{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- المستودع -->
                        <div>
                            <label for="warehouse_id" class="block mb-1">اسم المستودع</label>
                            <select name="warehouse_id" id="warehouse_id" class="w-full tom-select">
                                <option value="">اختر المستودع</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- الشركة المصنعة -->
                        <div>
                            <label for="manufacturer_id" class="block mb-1">الشركة المصنعة</label>
                            <select name="manufacturer_id" class="w-full tom-select">
                                <option value="">اختر الشركة</option>
                                @foreach ($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}"
                                        {{ request('manufacturer_id') == $manufacturer->id ? 'selected' : '' }}>
                                        {{ $manufacturer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- البراند -->
                        <div>
                            <label for="brand_id" class="block mb-1">البراند</label>
                            <select name="brand_id" class="w-full tom-select">
                                <option value="">اختر البراند</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- المورد -->
                        <div>
                            <label for="supplier_id" class="block mb-1">المورد</label>
                            <select name="supplier_id" class="w-full tom-select">
                                <option value="">اختر المورد</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="flex gap-4">
                            <label><input type="checkbox" name="expired" value="1" {{ request('expired') ? 'checked' : '' }}> منتهية الصلاحية</label>
                            <label><input type="checkbox" name="near_expiry" value="1" {{ request('near_expiry') ? 'checked' : '' }}> قاربت على الانتهاء</label>
                            <label><input type="checkbox" name="reorder" value="1" {{ request('reorder') ? 'checked' : '' }}> وصلت حد الطلب</label>
                            <label><input type="checkbox" name="surplus" value="1" {{ request('surplus') ? 'checked' : '' }}> فائض</label>
                        </div> --}}
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="expired" value="1" {{ request('expired') ? 'checked' : '' }}>
                                منتهية الصلاحية
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="near_expiry" value="1" {{ request('near_expiry') ? 'checked' : '' }}>
                                قاربت على الانتهاء
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="reorder" value="1" {{ request('reorder') ? 'checked' : '' }}>
                                وصلت حد الطلب
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="surplus" value="1" {{ request('surplus') ? 'checked' : '' }}>
                                فائض
                            </label>
                        </div>
                        
                        
                    </div>

                    <!-- أزرار التصفية والتفريغ -->
                    <div class="mt-4 flex gap-4">
                        <div class="col-md-12 mt-1 hide-on-print">
                            <button type="submit" name="filter" value="1"
                                class=" btn btn-primary text-indigo-600 hover:text-indigo-700">
                                تصفية</button>
                        </div>
                        <!-- زر تفريغ الفلاتر -->
                        <div class="hide-on-print  mt-1 hide-on-print">
                            <button type="button" id="resetFilters"
                                class="btn btn-secondary bg-gray-300 hover:bg-gray-500 text-gray-700">تفريغ الفلاتر</button>
                        </div>
                        
                  
                </form>
            </div>
        </div>

        <!-- محتوى التقرير -->
        <main>
            @if ($products->isEmpty())
                <p class="text-red-500 text-center">لا توجد منتجات لعرضها في التقرير.</p>
            @else
                <table class="w-full border-collapse border border-gray-300 text-sm mt-4">
                    <thead>
                        <tr class=" text-gray-900 dark:text-gray-100">
                            <th class="border p-2">المستودع</th>
                            <th class="border p-2">اسم المنتج</th>
                            <th class="border p-2">SKU</th>
                            <th class="border p-2">الباركود</th>
                            <th class="border p-2">الشركة المصنعة</th>
                            <th class="border p-2">البراند</th>
                            <th class="border p-2"> الكمية المتاحة</th>
                            <th class="border p-2">المورد</th>
                            <th class="border p-2">الحد الأدنى</th>
                            <th class="border p-2">الحد الأقصى</th>
                            <th class="border p-2">المكونات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="border p-2">
                                    @if($product->warehouses && $product->warehouses->count())
                                        <ul class="list-disc list-inside">
                                            @foreach ($product->warehouses as $warehouse)
                                                <li>{{ $warehouse->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        لا يوجد مستودع
                                    @endif
                                </td>
                                
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->name }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->sku }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->barcode }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($product->manufacturingCountry)->name ?? 'غير متاح' }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($product->brand)->name ?? 'غير متاح' }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($product)->total_quantity ?? 'غير متاح' }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($product->supplier)->name ?? 'غير متاح' }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->min_stock_level ?? 'غير محدد' }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->max_stock_level ?? 'غير محدد' }}</td>
                                <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                    @if (!empty($product->ingredients))
                                        <ul class="list-disc list-inside">
                                            @foreach (explode('،', $product->ingredients) as $ingredient)
                                                <li>{{ trim($ingredient) }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        لا توجد مكونات
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </main>

        <!-- فوتر التقرير -->
        <footer class="text-center mt-6">
            <p>تمت الطباعة بواسطة: {{ auth()->user()->name }} | تاريخ الطباعة:
                {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
        </footer>

    </div>
</x-layout>
