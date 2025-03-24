<x-layout>
    <div class="container mx-auto p-4">
        <header class="text-center mb-4">
            <h1 class="text-2xl font-bold">تقرير الموردين</h1>
            <hr class="border-t border-gray-300 mt-2">
        </header>

        <!-- نموذج البحث -->
        <form action="{{ route('reports.search-partners') }}" method="GET" class="mb-4">
            <div class="flex flex-wrap justify-between">
                <div class="mb-2 w-full md:w-1/4">
                    <label for="name" class="block">اسم المنتج</label>
                    <input type="text" id="name" name="name" value="{{ request('name') }}" class="border p-2 w-full">
                </div>
                <div class="mb-2 w-full md:w-1/4">
                    <label for="sku" class="block">رقم المنتج (SKU)</label>
                    <input type="text" id="sku" name="sku" value="{{ request('sku') }}" class="border p-2 w-full">
                </div>
                <div class="mb-2 w-full md:w-1/4">
                    <label for="warehouse_id" class="block">المستودع</label>
                    <select name="warehouse_id" id="warehouse_id" class="border p-2 w-full">
                        <option value="">اختر المستودع</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 w-full md:w-1/4 flex items-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2">بحث</button>
                </div>
            </div>
        </form>

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
                        <th class="border p-2">الشركاء الذين قاموا بحركات الشراء</th> <!-- عمود جديد لعرض الشركاء -->
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
                            <td class="border p-2">{{ $productDetail['last_purchase_date'] ?? 'غير متاح' }}</td>

                            <!-- عرض الشركاء الذين قاموا بحركات الشراء -->
                            <td class="border p-2">
                                @foreach ($purchasesByPartner[$productDetail['id']] ?? [] as $purchase)
                                    <p>{{ $purchase['partner_name'] }} - الكمية: {{ $purchase['quantity'] }} - تاريخ الحركة: {{ $purchase['transaction_date'] }}</p>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-layout>
