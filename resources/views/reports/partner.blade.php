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
                تقرير بيانات الشركاء
            </h1>
        </x-reportHeader>

        <!-- زر إظهار/إخفاء الفلاتر -->
        <div x-data="{ open: true }" class="mt-2 mb-2">
            <button type="button" @click="open = !open"
                class="hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>

            <!-- نموذج الفلترة -->
            <div x-show="open" x-transition>
                <form action="{{ route('reports.partner') }}" method="GET" class=" p-4 rounded shadow">
                    <div>
                        <label for="partner_name" class="block mb-1">اسم الشريك</label>
                        <input type="text" name="partner_name" value="{{ request('partner_name') }}" class="w-full">
                    </div>

                    <!-- نوع الشريك -->
                    <div>
                        <label for="type" class="block mb-1">نوع الشريك</label>
                        <select name="type" class="w-full tom-select">
                            <option value="">اختر النوع</option>
                            @foreach ($partnerTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- حالة الشريك -->
                    <div>
                        <label for="is_active" class="block mb-1">حالة الشريك</label>
                        <select name="is_active" class="w-full tom-select">
                            <option value="">اختر الحالة</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <select id="warehouse_id" name="warehouse_id">
                        <option value="">اختر مستودعًا</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    
                    <select id="product_id" name="product_id">
                        <option value="">اختر منتجًا</option>
                        <!-- هنا ستتم إضافة المنتجات التي سيتم تحميلها ديناميكيًا -->
                    </select>
                    
                    <!-- فلترة الحركات -->
                    <div>
                        <label for="has_transactions" class="block mb-1">فلترة الموردين حسب الحركات</label>
                        <select name="has_transactions" class="w-full tom-select">
                            <option value="">اختر التصفية</option>
                            <option value="1" {{ request('has_transactions') == '1' ? 'selected' : '' }}>الموردين
                                مع الحركات فقط</option>
                            <option value="0" {{ request('has_transactions') == '0' ? 'selected' : '' }}>جميع
                                الموردين</option>
                        </select>
                    </div>

                    <!-- أزرار التصفية والتفريغ -->
                    <div class="mt-4 flex gap-4">
                        <button type="submit"
                            class=" hide-on-print btn btn-primary text-white bg-indigo-600 hover:bg-indigo-700">تصفية</button>

                        <a href="{{ route('reports.partner') }}"
                            class="hide-on-print btn bg-gray-300 hover:bg-gray-400 text-gray-800">تفريغ الفلاتر</a>
                    </div>
                </form>

            </div>
        </div>

        <!-- محتوى التقرير -->
        <main>
            @if ($partners->isEmpty())
                <p class="text-red-500 text-center">لا توجد بيانات للشركاء لعرضها في التقرير.</p>
            @else
                <table class="w-full border-collapse border border-gray-300 text-sm mt-4">
                    <thead>
                        <tr class=" text-gray-900 dark:text-gray-100">
                            <th class="border p-2">اسم الشريك</th>
                            <th class="border p-2">نوع الشريك</th>
                            <th class="border p-2">الشخص المسؤول</th>
                            <th class="border p-2">رقم الهاتف</th>
                            <th class="border p-2">البريد الإلكتروني</th>
                            <th class="border p-2">العنوان</th>
                            <th class="border p-2">رقم الضريبة</th>
                            <th class="border p-2">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <td class="border p-2">{{ $partner->name }}</td>
                                <td class="border p-2">{{ $partner->partnerType->name ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $partner->contact_person ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $partner->phone ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $partner->email ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $partner->address ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $partner->tax_number ?? 'غير متاح' }}</td>
                                <td class="border p-2">{{ $partner->is_active == 1 ? 'نشط' : 'غير نشط' }}</td>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const warehouseSelect = document.getElementById('warehouse_id');
        const productSelect = document.getElementById('product_id');
        
        warehouseSelect.addEventListener('change', function () {
            const warehouseId = warehouseSelect.value;
            // console.log(`/products-by-warehouse?warehouse_id=${warehouseId}`);

            // إرسال طلب AJAX لتحميل المنتجات بناءً على المستودع
            fetch(`/reports/products-by-warehouse?warehouse_id=${warehouseId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);  // انظر إلى محتوى الاستجابة

                    // إفراغ قائمة المنتجات الحالية
                    productSelect.innerHTML = '<option value="">اختر منتجًا</option>';

                    // إضافة الخيارات الجديدة للمنتجات
                    data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        productSelect.appendChild(option);
                    });
                });
        });
    });
</script>
