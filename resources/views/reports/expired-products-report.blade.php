<x-layout>
    <div class="container ">
        <!-- زر الطباعة - يظهر فقط عند العرض العادي -->
        <div class="hide-on-print text-right mt-2 mb-4">
            <button onclick="window.print()"
                class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                التقرير
            </button>
        </div>

        <!-- رأس التقرير -->
        {{-- <x-reportHeader :company="$company" :warehouse="$warehouse"> --}}
        <x-reportHeader>
            <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300"> تقرير المنتجات المقاربة
                لإنتهاء الصلاحية </h1>
        </x-reportHeader>

        {{-- زر لفتح أو إغلاق القسم --}}
        <div x-data="{ open: true }">
            <button type="button" @click="open = !open"
                class=" hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>

            {{-- نموذج الفلترة --}}
            <div x-show="open" x-transition>
                <form method="GET" action="{{ route('reports.expired-products') }}" class="mb-4  " id="filter-form">

                    <div class="flex flex-wrap gap-4 items-end">
                        <!-- اختيار المستودع -->
                        <div class="flex-1 min-w-[250px]">
                            <label for="warehouse_id" class="form-label"> اسم المستودع</label>
                            <select name="warehouse_id" id="warehouse_id"
                                class="w-full tom-select border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                <option value="">كل المستودعات</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- اختيار المنتج -->
                        <div class="flex-1 min-w-[250px]">
                            <div class=" mb-2">
                                <label for="name" class="block">اسم المنتج/الباركود/SKU </label>
                                <select name="products[]"
                                    class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                    <option value="">اختر منتج</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}-{{ $product->barcode }}--{{ $product->sku }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- من تاريخ -->
                        <div class="flex-1 min-w-[200px]">
                            <x-file-input id="expiration_from" name="expiration_from" label="من تاريخ" type="date"
                                :value="request('expiration_from')" />
                        </div>

                        <!-- إلى تاريخ -->
                        <div class="flex-1 min-w-[200px]">
                            <x-file-input id="expiration_to" name="expiration_to" label="إلى تاريخ" type="date"
                                :value="request('expiration_to')" />
                        </div>
                    </div>

                    <!-- زر الفلترة -->
                    <div class="col-md-12 mt-4 hide-on-print">
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
    </div>


    <div class="container mx-auto p-4">


        <!-- محتوى التقرير -->
        <main>
            @if ($report->isEmpty())
                <p class="text-red-500 text-center">لا توجد منتجات مقاربة لإنتهاء الصلاحية بناءً على الفلترة.</p>
            @else
                <div class="">
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">اسم المنتج</th>
                                <th class="border p-2">رقم المنتج (SKU)</th>
                                <th class="border p-2">تاريخ الإنتاج</th>
                                <th class="border p-2">تاريخ انتهاء الصلاحية</th>
                                <th class="border p-2">الكمية المتاحة</th>
                                <th class="border p-2">المستودع</th>
                                <th class="border p-2">منطقة التخزين</th>
                                <th class="border p-2">موقع التخزين</th>
                                <th class="border p-2">الكمية الإجمالية</th>
                                <th class="border p-2">الإجراءات المتخذة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $item)
                                @php $inventoryCount = $item->inventoryProducts->count(); @endphp
                                <tr>
                                    <td rowspan="{{ max($inventoryCount, 1) }}" class="border p-2">
                                        {{ $item->product->name ?? 'غير متاح' }}</td>
                                    <td rowspan="{{ max($inventoryCount, 1) }}" class="border p-2">
                                        {{ $item->product->sku ?? 'غير متاح' }}</td>
                                    <td rowspan="{{ max($inventoryCount, 1) }}" class="border p-2">
                                        {{ $item->production_date ? \Carbon\Carbon::parse($item->production_date)->format('Y-m-d') : 'غير متاح' }}
                                    </td>
                                    <td rowspan="{{ max($inventoryCount, 1) }}" class="border p-2">
                                        {{ \Carbon\Carbon::parse($item->expiration_date)->format('Y-m-d') }}</td>
                                    <td rowspan="{{ max($inventoryCount, 1) }}" class="border p-2">
                                        {{ $item->quantity }}</td>
                                    <td rowspan="{{ max($inventoryCount, 1) }}" class="border p-2">
                                        {{ optional($item->warehouseLocation)->name ?? 'غير متاح' }}</td>

                                    @if ($inventoryCount > 0)
                                        @foreach ($item->inventoryProducts as $index => $inventoryProduct)
                                            @if ($index > 0)
                                <tr>
                            @endif
                            <td class="border p-2">{{ optional($inventoryProduct->storageArea)->name ?? 'غير متاح' }}
                            </td>
                            <td class="border p-2">{{ optional($inventoryProduct->location)->name ?? 'غير متاح' }}</td>
                            @if ($index === 0)
                                <td rowspan="{{ $inventoryCount }}" class="border p-2">
                                    {{ $item->inventory_products_sum_quantity ?? 0 }}</td>
                                <td rowspan="{{ $inventoryCount }}" class="border p-2">
                                    <button class=" px-2 py-1">تم التخلص منها</button>
                                </td>
                            @endif
                            @if ($index > 0)
                                </tr>
                            @endif
            @endforeach
        @else
            <td class="border p-2">غير متاح</td>
            <td class="border p-2">غير متاح</td>
            <td class="border p-2">{{ $item->inventory_products_sum_quantity ?? 0 }}</td>
            <td class="border p-2">
                <button class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">تم التخلص منها</button>
            </td>
            @endif
            </tr>
            @endforeach
            </tbody>
            </table>
            @endif
        </main>

        <!-- فوتر التقرير - يختفي أثناء الطباعة -->
        <footer class="text-center mt-6">
            <p>تمت الطباعة بواسطة: {{ auth()->user()->name }} | تاريخ الطباعة:
                {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>

        </footer>
    </div>


</x-layout>
<script>
    document.getElementById('resetFilters').addEventListener('click', function() {
        // حفظ موضع التمرير لمنع اهتزاز الصفحة عند إعادة التعيين
        const scrollY = window.scrollY;

        // إعادة تعيين جميع الحقول داخل النموذج
        const form = document.getElementById('filter-form');
        form.reset();

        // إعادة تعيين حقول التاريخ يدويًا (لأن reset() لا يعيدها)
        form.querySelectorAll('input[type="date"]').forEach(input => {
            input.value = '';
        });

        // إعادة تعيين حقول checkbox يدويًا
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // إعادة تعيين الحقول التي تستخدم TomSelect
        document.querySelectorAll('.tom-select').forEach(select => {
            if (select.tomselect) {
                select.tomselect.clear(); // يمسح جميع الاختيارات داخل TomSelect
            }
        });

        // إعادة موضع التمرير بعد إعادة التعيين
        setTimeout(() => {
            window.scrollTo(0, scrollY);
        }, 50);
    });


    document.addEventListener("DOMContentLoaded", function() {
        const tomSelects = document.querySelectorAll('.tom-select');
        document.getElementById('filter-form').reset();

        tomSelects.forEach(select => {
            if (!select.tomselect) {
                new TomSelect(select, {
                    onChange: function() {
                        // حفظ موضع التمرير قبل التحديث
                        const scrollY = window.scrollY;

                        // تأخير بسيط ثم إعادة التمرير إلى الموضع السابق
                        setTimeout(() => {
                            window.scrollTo(0, scrollY);
                        }, 50);
                    }
                });
            }
        });
    });
</script>
