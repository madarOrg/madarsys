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
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="">
                                <th class="py-4">الرقم</th>
                                <th class="px-6 py-3">اسم المنتج</th>
                                <th class="px-6 py-3">الكمية</th>
                                <th class="px-6 py-3">موقع المنتج</th>
                                <th class="px-6 py-3">المنطقة التخزينية</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"                                  >
                                    <td class="">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">{{ $product->product->name }}</td>
                                    <td class="px-6 py-4">{{ $product->quantity }}</td>
                                    <td class="px-6 py-4">{{ $product->location->full_location ?? 'غير محدد' }}</td>
                                    <td class="px-6 py-4">{{ $product->storageArea->area_name ?? 'غير محدد' }}</td>

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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("warehouse_id");
        select.addEventListener("change", function() {
            this.form.submit();
        });
    });
</script>
    