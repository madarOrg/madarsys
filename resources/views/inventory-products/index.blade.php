<x-layout>
    <section>
        <div>
            <x-title :title="'إدارة المنتجات في المستودعات'"></x-title>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                يرجى اختيار المستودع لعرض المنتجات المخزنة داخله.
            </p>

            <!-- اختيار المستودع -->
            <form method="GET" action="{{ route('inventory-products.index') }}">
                <div class="mb-4">
                    <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع"
                        :options="$warehouses->pluck('name', 'id')" 
                        onchange="this.form.submit()"
                        :selected="request()->warehouse_id"
                        required />
                </div>
            </form>

            <!-- عرض المنتجات في جدول -->
            @if($products->isNotEmpty())
                <div class="mt-6">
                    <h2 class="text-lg font-semibold">المنتجات في المستودع المحدد</h2>
                    <table class="w-full border border-gray-300 mt-4">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">الرقم</th>
                                <th class="border p-2">اسم المنتج</th>
                                <th class="border p-2">الكمية</th>
                                <th class="border p-2">الموقع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="border">
                                    <td class="p-2 border">{{ $loop->iteration }}</td>
                                    <td class="p-2 border">{{ $product->product->name }}</td>
                                    <td class="p-2 border">{{ $product->quantity }}</td>
                                    <td class="p-2 border">{{ $product->location->full_location ?? 'غير محدد' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 mt-4">لا توجد منتجات في هذا المستودع.</p>
            @endif
        </div>
    </section>
</x-layout>
