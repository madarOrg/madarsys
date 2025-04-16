<x-layout>
    <div class="container mx-auto p-4">
        <div x-data="{ open: true }">
            <button type="button" @click="open = !open"
                class=" hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>

            {{-- نموذج الفلترة --}}
            <div x-show="open" x-transition>
                <!-- نموذج البحث -->
                <form action="{{ route('reports.search-partners') }}" method="GET" class="" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                        <div class="t mb-2">
                            <label for="name" class="block">اسم المنتج/الباركود/SKU </label>
                            <select name="products[]"
                                class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                <option value="">اختر منتج</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}-{{ $product->barcode }}--{{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-2">
                            <label for="warehouse_id" class="block">اسم المستودع</label>
                            <select name="warehouse_id" id="warehouse_id" class="tom-select  ">
                                <option value="">اختر المستودع</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
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
                            class="btn btn-secondary bg-gray-300 hover:bg-gray-500 text-gray-700">تفريغ
                            الفلاتر</button>
                    </div>
                </form>
            </div>




            <div class="container mx-auto p-4">
                <!-- زر الطباعة - يظهر فقط عند العرض العادي -->
                <div class="hide-on-print text-right mt-2 mb-4 ">
                    <button onclick="window.print()"
                        class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                        التقرير
                    </button>
                </div>
                <!-- رأس التقرير -->
                <x-reportHeader :company="$company" :warehouse="$warehouse">
                    <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300 ">تقرير موردي المنتجات
                    </h1>
                </x-reportHeader>


                <!-- عرض النتائج -->
                @if (empty($purchasesByPartner) || count($purchasesByPartner) === 0)
                    <p class="text-center text-red-500">لا توجد منتجات .</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-2">اسم المنتج</th>
                                    <th class="border p-2">رقم المنتج (SKU)</th>
                                    <th class="border p-2">الباركود</th>
                                    <th class="border p-2">الوصف</th>
                                    <th class="border p-2">الكمية المتوفرة</th>
                                    <th class="border p-2">حد الطلب</th>
                                    <th class="border p-2">الشركاء الذين قاموا بحركات الشراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchasesByPartner as $details)
                                    <tr>
                                        <td class="border p-2">{{ $details['product']['name'] ?? 'غير متاح' }}</td>
                                        <td class="border p-2">{{ $details['product']['sku'] ?? 'غير متاح' }}</td>
                                        <td class="border p-2">{{ $details['product']['barcode'] ?? 'غير متاح' }}</td>
                                        <td class="border p-2">{{ $details['product']['description'] ?? 'غير متاح' }}
                                        </td>
                                        <td class="border p-2">{{ $details['product']['available_quantity'] ?? 0 }}
                                        </td>
                                        <td class="border p-2">{{ $details['product']['min_stock_level'] ?? 0 }}</td>
                                        <td class="border p-2">
                                            @if (!empty($details['purchases']))
                                                @foreach ($details['purchases'] as $purchase)
                                                    <p>
                                                        {{ $purchase['partner_name'] }} -
                                                        الكمية: {{ $purchase['quantity'] ?? 0 }}
                                                        {{ $purchase['unit_name'] }}
                                                        - تاريخ الحركة: {{ $purchase['transaction_date'] }}
                                                        @php $purchase = (object) $purchase; @endphp
                                                        الحالة: {{ __('statuses.' . (string) $purchase->status) }}
                                                    </p>
                                                @endforeach
                                            @else
                                                <p>لا توجد بيانات</p>
                                            @endif
                                        </td>
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
