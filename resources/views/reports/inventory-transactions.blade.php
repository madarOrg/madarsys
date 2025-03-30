<x-layout>
    <div class="container mx-auto p-4">

        <div x-data="{ open: true }">
            <button type="button" @click="open = !open"
                class=" hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 flex-1 min-w-[200px] ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' : '<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>

            {{-- نموذج الفلترة --}}

            <div x-show="open" x-transition>
                <form action="{{ route('reports.inventory-transactions') }}" method="GET" id="filter-form">
                    <div class="flex flex-wrap gap-2 items-end">

                        <!-- تصفية حسب المنتج -->
                        <div class="mb-2 flex-1 min-w-[200px]">
                            <label for="products" class="block">اسم المنتج/الباركود/SKU</label>
                            <select name="products[]"
                                class="w-full product-select tom-select  min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500"
                                multiple>
                                <option value="">اختر منتج</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ in_array($product->id, request('products', [])) ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->barcode }} - {{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- تصفية حسب المستودع -->
                        <div class="mb-2 flex-1 min-w-[200px]">
                            <label for="warehouse_id" class="block">اسم المستودع</label>
                            <select name="warehouse_id" id="warehouse_id" class="tom-select ">
                                <option value="">اختر المستودع</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- تصفية حسب نوع الحركة -->
                        <div class="mb-2 flex-1 min-w-[200px]">
                            <label for="transaction_type" class="block">نوع الحركة</label>
                            <select name="transaction_type_id" id="transaction_type_id" class="tom-select">
                                <option value="">كل الحركات</option>
                                @foreach ($TransactionType as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request('transaction_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- من تاريخ -->
                        <div class="mb-2 flex-1 min-w-[200px]">
                            <x-file-input id="created_at_from" name="created_at_from" label="من تاريخ الإدخال"
                                type="date" :value="request('created_at_from')" />
                        </div>

                        <!-- إلى تاريخ -->
                        <div class="mb-2 flex-1 min-w-[200px]">
                            <x-file-input id="created_at_to" name="created_at_to" label="إلى تاريخ الإدخال"
                                type="date" :value="request('created_at_to')" />
                        </div>


                    </div>
                    <!-- زر التصفية -->
                    <div class="hide-on-print  mt-1">
                        <button type="submit"
                            class=" btn btn-primary text-indigo-600 hover:text-indigo-700">تصفية</button>
                    </div>

                    <!-- زر تفريغ الفلاتر -->
                    <div class="hide-on-print  mt-1">
                        <button type="button" id="resetFilters"
                            class="btn btn-secondary bg-gray-300 hover:bg-gray-500 text-gray-700">تفريغ الفلاتر</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="container mx-auto p-4">
            <div class="hide-on-print text-right mb-4">
                <button onclick="window.print()"
                    class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                    التقرير</button>
            </div>
            <x-reportHeader :company="$company" :warehouse="$warehouse">
                <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300">تقرير الحركات 
                    المخزنية</h1>
            </x-reportHeader>
            @if (empty($inventoryMovements) || count($inventoryMovements) === 0)
                <p class="text-center text-red-500">لا توجد حركات مخزنية.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">رقم الحركة</th>
                                <th class="border p-2">تاريخ الحركة</th>
                                <th class="border p-2">نوع الحركة</th>
                                <th class="border p-2">اسم المنتج</th>
                                <th class="border p-2">الشريك</th>
                                <th class="border p-2">المستودع</th>
                                <th>المستخدم المدخل </th>
                                <th>المستخدم المعدل</th>

                                <th>تاريخ الإضافة</th>

                                <th>تاريخ التعديل</th>
                                <th class="border p-2">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventoryMovements as $movement)
                                <tr>
                                    <td class="border p-2">{{ $movement->id ?? 'غير متاح' }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">

                                        {{ $movement->transaction_date ?? 'غير متاح' }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ $movement->transactionType->name ?? 'غير معروف' }}

                                    </td>
                                    <td class="border p-2 min-w-[250px] whitespace-nowrap">

                                        @if ($movement->items->isNotEmpty())
                                            @foreach ($movement->items as $item)
                                                <div>
                                                    <a href="{{ route('products.show', $item->product->id) }}"
                                                        class="font-medium text-indigo-600 hover:text-indigo-700 hover:underline">
                                                        {{ $item->product->name }}- {{ $item->product->sku }}
                                                    </a>
                                                    <span> - الكمية: {{ $item->quantity }}
                                                        {{ $item->unit->name ?? '' }}</span>
                                                </div>
                                                @if (!$loop->last)
                                                    <hr class="my-1">
                                                @endif
                                            @endforeach
                                        @else
                                            غير متاح
                                        @endif
                                    </td>

                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ $movement->partner->name ?? 'غير متاح' }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ $movement->warehouse->name ?? 'غير متاح' }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ optional($movement->createdUser)->name ?? 'غير معروف' }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ optional($movement->updatedUser)->name ?? 'غير معدل' }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ $movement->created_at }}</td>

                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ $movement->updated_at }}</td>
                                    <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                        {{ $movement->notes ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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
