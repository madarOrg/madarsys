<x-layout>
<form action="{{ route('inventory-products.search') }}" method="GET">
        <div class="accordion" id="searchAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    ننن
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        الفلاتر
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#searchAccordion">
                    <div class="accordion-body">
                        <div>
                            <label for="production_date_from">تاريخ الإنتاج من:</label>
                            <input type="date" name="production_date_from" id="production_date_from" value="{{ request('production_date_from') }}">
                        </div>
                        <div>
                            <label for="production_date_to">تاريخ الإنتاج إلى:</label>
                            <input type="date" name="production_date_to" id="production_date_to" value="{{ request('production_date_to') }}">
                        </div>
                        <div>
                            <label for="expiration_date_from">تاريخ الانتهاء من:</label>
                            <input type="date" name="expiration_date_from" id="expiration_date_from" value="{{ request('expiration_date_from') }}">
                        </div>
                        <div>
                            <label for="expiration_date_to">تاريخ الانتهاء إلى:</label>
                            <input type="date" name="expiration_date_to" id="expiration_date_to" value="{{ request('expiration_date_to') }}">
                        </div>
                        <div>
                            <label for="storage_area_id">المنطقة:</label>
                            <select name="storage_area_id" id="storage_area_id">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ request('storage_area_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="location_id">الرف:</label>
                            <select name="location_id" id="location_id">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->rack_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="created_at_from">تاريخ الإدخال من:</label>
                            <input type="date" name="created_at_from" id="created_at_from" value="{{ request('created_at_from') }}">
                        </div>
                        <div>
                            <label for="created_at_to">تاريخ الإدخال إلى:</label>
                            <input type="date" name="created_at_to" id="created_at_to" value="{{ request('created_at_to') }}">
                        </div>
                        <button type="submit">بحث</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- عرض النتائج بعد البحث -->
    @if($products->isEmpty())
        <p>لا توجد نتائج للبحث</p>
    @else
        <!-- عرض المنتجات -->
        @foreach($products as $product)
            <div>{{ $product->product_name }}</div>
            <div class="mb-4">
                <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع" :options="$warehouses->pluck('name', 'id')"
                    onchange="this.form.submit()" :selected="request()->warehouse_id" required />
            </div>
        </form>

        <!-- عرض المنتجات في جدول -->
        @if ($products->isNotEmpty())
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
                            <th class="px-6 py-3">تاريخ الإنتاج</th>
                            <th class="px-6 py-3">تاريخ الانتهاء</th>
                            <th class="px-6 py-3">رقم الدفعة</th>
                            <th class="px-6 py-3">رقم الحركة</th>
                            <th class="px-6 py-3">اجمالي الكمبة</th>


                            <th class="px-6 py-3">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr
                                class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                <td class="">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $product->product->name }}</td>
                                <td class="px-6 py-4">{{ $product->productQuantity ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">{{ $product->location->rack_code ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">{{ $product->storageArea->area_name ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">{{ $product->production_date ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">{{ $product->expiration_date ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">{{ $product->batch_number ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">{{ $product->inventory_transaction_item_id ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product->quantity ?? 'غير محدد' }} /
                                    {{ $distributedQuantities[$product->id] ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('inventory-products.new', [
                                        'warehouse_id' => $product->warehouse_id,
                                        'inventory_transaction_item_id' => $product->inventory_transaction_item_id,
                                        'product_id' => $product->product_id,
                                    ]) }}"
                                        class="text-orange-400 hover:underline dark:text-white-500">
                                        <i class="fas fa-truck"></i> <!-- توزيع عبر النقل -->
                                    </a>
                                    <a href="{{ route('inventory-products.edit', $product->id) }}"
                                        class="text-blue-600 hover:underline dark:text-blue-500">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form id="delete-form-{{ $product->id }}"
                                        action="{{ route('inventory-products.destroy', $product->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button onclick="confirmDelete({{ $product->id }})"
                                        class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Links for Pagination -->
                <div class="mt-4">
                    <x-pagination-links :paginator="$products" />

                </div>
            </div>
        @else
            <p class="text-gray-500 mt-4">لا توجد منتجات في هذا المستودع.</p>
        @endif
        </div>
    </section>
            <!-- باقي البيانات هنا -->
        @endforeach
        {{ $products->links() }} <!-- روابط التنقل بين الصفحات -->
    @endif
    
    <section>
        <div class="flex items-center ">
            <x-title :title="'إدارة المنتجات في المستودعات'"></x-title>

            <x-button :href="route('inventory-products.create')" type="button" class="ml-4">
                <i class="fas fa-plus mr-2"></i> إضافة منتج جديد إلى مستودع
            </x-button>
        </div>

        <p class="text-sm ">
            يرجى اختيار المستودع لعرض المنتجات المخزنة داخله.
        </p>

        <!-- اختيار المستودع -->
        <form method="GET" action="{{ route('inventory-products.index') }}">

</x-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("warehouse_id");
        select.addEventListener("change", function() {
            this.form.submit();
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
