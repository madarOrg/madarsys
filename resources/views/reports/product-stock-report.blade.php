<x-layout>
    <div class="container">

        <!-- زر الطباعة -->
        <div class="hide-on-print text-right mb-4">
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
                class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>

            <!-- نموذج الفلترة -->
            <div x-show="open" x-transition>
                <form action="{{ route('reports.product-stock') }}" method="GET" class="bg-gray-50 p-4 rounded shadow">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                    </div>

                    <!-- أزرار التصفية والتفريغ -->
                    <div class="mt-4 flex gap-4">
                        <button type="submit"
                            class="btn btn-primary text-white bg-indigo-600 hover:bg-indigo-700">تصفية</button>

                        <a href="{{ route('reports.product-stock') }}"
                            class="btn bg-gray-300 hover:bg-gray-400 text-gray-800">تفريغ الفلاتر</a>
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
                        <tr class="bg-gray-100 text-gray-900 dark:text-gray-100">
                            <th class="border p-2">المستودع</th>
                            <th class="border p-2">اسم المنتج</th>
                            <th class="border p-2">SKU</th>
                            <th class="border p-2">الباركود</th>
                            <th class="border p-2">الشركة المصنعة</th>
                            <th class="border p-2">البراند</th>
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
                                
                                <td class="border p-2">{{ $product->name }}</td>
                                <td class="border p-2">{{ $product->sku }}</td>
                                <td class="border p-2">{{ $product->barcode }}</td>
                                <td class="border p-2">{{ optional($product->manufacturer)->name ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ optional($product->brand)->name ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ optional($product->supplier)->name ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $product->min_quantity ?? 'غير محدد' }}</td>
                                <td class="border p-2">{{ $product->max_quantity ?? 'غير محدد' }}</td>
                                <td class="border p-2">
                                    @if (!empty($product->components))
                                        <ul class="list-disc list-inside">
                                            @foreach ($product->components as $component)
                                                <li>{{ $component }}</li>
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
