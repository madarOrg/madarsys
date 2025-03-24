<x-layout>
    <div class="container">

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
                <form action="{{ route('reports.search-products') }}" method="GET" class="">
                    <div class="">
                      
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div class="hide-on-print mb-2">
                                    <label for="name" class="block">اسم المنتج</label>
                                    <input type="text" id="name" name="name" value="{{ request('name') }}" class="tom-select ">
                                </div>
                                <div class="hide-on-print mb-2">
                                    <label for="sku" class="block">رقم المنتج (SKU)</label>
                                    <input type="text" id="sku" name="sku" value="{{ request('sku') }}" class="tom-select  ">
                                </div>
                                <div class="mb-2">
                                    <label for="warehouse_id" class="block">المستودع</label>
                                    <select name="warehouse_id" id="warehouse_id" class="tom-select  ">
                                        <option value="">اختر المستودع</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mt-1 ">
                                    <button type="submit" name="filter" value="1"
                                        class=" hide-on-print btn btn-primary  text-red-500">
                                        تصفية</button>
                                </div>
                            </div>
                     
                    </div>
                    
                </form>
            </div>
        </div>
        
        <div class="container mx-auto p-4">
            <!-- زر الطباعة - يظهر فقط عند العرض العادي -->
            <div class="hide-on-print text-right mb-4 ">
                <button onclick="window.print()"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    طباعة التقرير
                </button>
            </div>
            <!-- رأس التقرير -->
            <header class="mb-0">
                <div class="flex items-center justify-between mb-0">
                    <div class="text-right">
                        <h1 class="text-2xl font-bold">{{ $company->name ?? 'غير متاح' }}</h1>
                        <h3 class="text-lg">المستودع: {{ $warehouse->name ?? 'غير متاح' }}</h3>
                    </div>
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة"
                        class="w-16 h-16  rounded-full">
                </div>
                <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300 ">تقرير المنتجات التي وصلت لحد إعادة الطلب</h1>
                {{-- <hr class="border-t border-gray-300"> --}}
            </header>

            @if ($reorderProducts->isEmpty())
                <p class="text-center text-red-500">لا توجد منتجات وصلت لحد إعادة الطلب.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">اسم المنتج</th>
                                <th class="border p-2">رقم المنتج (SKU)</th>
                                <th class="border p-2">وصف مختصر</th>
                                <th class="border p-2">الكمية المتوفرة</th>
                                <th class="border p-2">مستوى إعادة الطلب</th>
                                <th class="border p-2">تاريخ آخر طلب شراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reorderProducts as $productDetail)
                                <tr>
                                    <td class="border p-2">{{ $productDetail['name'] }}</td>
                                    <td class="border p-2">{{ $productDetail['sku'] }}</td>
                                    <td class="border p-2">{{ $productDetail['description'] }}</td>
                                    <td class="border p-2">{{ $productDetail['available_quantity'] }}</td>
                                    <td class="border p-2">{{ $productDetail['min_stock_level'] }}</td>
                                    <td class="border p-2">{{ $productDetail['last_purchase_date'] ?? 'غير متاح' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
</x-layout>
