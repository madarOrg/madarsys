<x-layout>
    <div class="container">
        <form method="GET" action="{{ route('inventory.audit.report') }}" class="mb-3">
            <x-reportHeader>
                <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300"> تقارير قوائم الجرد</h1>
            </x-reportHeader>
            <div x-data="{ open: true }">
                <!-- زر لفتح أو إغلاق القسم -->
                <button type="button" @click="open = !open"
                    class="hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                    <span
                        x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                    </span>
                </button>

                <!-- الحقول القابلة للطي -->
                <div x-show="open" x-transition>
                    <!-- فلترة حسب حالة المخزون -->
                    <div class="flex flex-wrap gap-2">
                        <div class="w-1/5">
                            <label class="form-label">حالة المخزون:</label>
                            <select name="stock_status"
                                class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                <option value="">الكل</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>منخفض
                                </option>
                                <option value="excess" {{ request('stock_status') == 'excess' ? 'selected' : '' }}>فائض
                                </option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>غير
                                    متوفر
                                </option>
                            </select>
                        </div>

                        <!-- فلترة حسب نوع الجرد -->
                        <div class="w-1/5">
                            <label class="form-label">نوع الفلترة:</label>
                            <select name="filter"
                                class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                <option value="">اختر نوع الفلترة</option>
                                <option value="recent" {{ request('filter') == 'recent' ? 'selected' : '' }}>
                                    تم الجرد حديثاً
                                </option>
                                <option value="never" {{ request('filter') == 'never' ? 'selected' : '' }}>
                                    لم يُجرَد أبداً
                                </option>
                                <option value="not_since" {{ request('filter') == 'not_since' ? 'selected' : '' }}>
                                    لم يُجرَد منذ تاريخ
                                </option>
                            </select>
                        </div>

                        <!-- حقل التاريخ يظهر عند اختيار فلترة "not_since" -->
                        <div class="w-1/5">
                            <label class="form-label">من تاريخ:</label>
                            <input type="date" name="given_date"
                                class="form-control w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                value="{{ request('given_date') }}">
                        </div>

                        <!-- فلترة حسب التصنيف -->
                        <div class="w-1/5">
                            <label class="form-label">فئات المنتج:</label>
                            <select name="category_id" id="category_id" class="tom-select ">
                                <option value="">كل التصنيفات</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- البحث باسم المنتج أو SKU -->
                        <div class="w-1/5">
                            <label class="form-label">اسم المنتج/SKU:</label>
                            <input type="text" name="search" class="form-control tom-select "
                                placeholder="اسم المنتج أو SKU" value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="hide-on-print  mb-4 mt-1">
                        <button type="submit"
                            class=" btn btn-primary text-indigo-600 hover:text-indigo-700">تصفية</button>
                        <button type="button" id="resetFilters"
                            class="btn btn-secondary bg-gray-300 hover:bg-gray-500 text-gray-700">تفريغ الفلاتر</button>
                    </div>
                </div>
            </div>
    </div>
    </form>
    <div class="container ">
        <!-- زر الطباعة - يظهر فقط عند العرض العادي -->
        <div class="hide-on-print text-right mb-4">
            <button onclick="window.print()"
                class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                التقرير
            </button>

        </div>
   

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">

                        <th class="border p-2">الاسم</th>
                        <th class="border p-2">SKU</th>
                        <th class="border p-2">الكمية المتوفرة</th>
                        <th class="border p-2">آخر عملية جرد</th>
                        <th class="border p-2">الحالة</th>
                        <th class="border p-2">الكميه</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->name }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->sku }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                {{ $product->inventory->quantity ?? 0 }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                {{ $product->last_inventory_date ? $product->last_inventory_date->format('Y-m-d') : 'غير متوفر' }}
                            </td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->status }}
                            </td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                {{ $product->inventoryTransactions->sum('quantity') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد نتائج</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- {{ $products->links() }} --}}
        </div>
    </div>

</x-layout>
