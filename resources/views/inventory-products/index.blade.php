<x-layout>

    <form action="{{ route('inventory-products.search') }}" method="GET" class="p-1 rounded-lg shadow mb-2">
        <div x-data="{ open: true }">
            <!-- زر لفتح أو إغلاق القسم -->
            <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                <span
                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                </span>
            </button>


            <!-- الحقول القابلة للطي -->
            <div x-show="open" x-transition>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                      
                    <x-select-dropdown 
                    id="distribution_type" 
                    name="distribution_type" 
                    label="النوع" 
                    :options="[
                        '' => 'الكل',
                        '1' => ' إدخال مخزني',
                        '-1' => 'إخراج مخزني'
                    ]" 
                    :selected="request('distribution_type')" 
                    onchange="this.form.submit()" />
                
                    <!-- اختيار المستودع -->
                    <x-select-dropdown id="warehouse_id" name="warehouse_id" label="المستودع" :options="$warehouses->pluck('name', 'id')"
                        onchange="this.form.submit()" :selected="request()->warehouse_id" required />

                    <!-- المنطقة التخزينية -->
                    <x-select-dropdown id="storage_area_id" name="storage_area_id" label="المنطقة التخزينية"
                        :options="$storageAreas->pluck('area_name', 'id')" 
                     :selected="request()->storage_area_id" required />

                    <!-- موقع المنتج -->
                    <x-select-dropdown id="location_id" name="location_id" label="موقع المنتج" :options="$locations"
                  :selected="request()->location_id" required />

                    <!-- تواريخ الإنتاج -->
                    <x-file-input id="production_date_from" name="production_date_from" label="إنتاج من:" type="date"
                        value="{{ request('production_date_from') }}" />

                    <x-file-input id="production_date_to" name="production_date_to" label="إنتاج إلى:" type="date"
                        value="{{ request('production_date_to') }}" />

                    <!-- تواريخ الانتهاء -->
                    <x-file-input id="expiration_date_from" name="expiration_date_from" label="انتهاء من:"
                        type="date" value="{{ request('expiration_date_from') }}" />

                    <x-file-input id="expiration_date_to" name="expiration_date_to" label="انتهاء إلى:" type="date"
                        value="{{ request('expiration_date_to') }}" />

                    <!-- تواريخ الإدخال -->
                    <x-file-input id="created_at_from" name="created_at_from" label="إدخال من:" type="date"
                        value="{{ request('created_at_from') }}" />

                    <x-file-input id="created_at_to" name="created_at_to" label="إدخال إلى:" type="date"
                        value="{{ request('created_at_to') }}" />
                    <!-- بحث عن الحركة -->
                    <x-file-input id="inventory_transaction_item_id" name="inventory_transaction_item_id"
                        label="رقم الحركة" value="{{ request('inventory_transaction_item_id') }}" />

                    <!-- بحث عن المنتج -->
                    <x-file-input id="product_name" name="product_name" label="اسم /رقم / باركود/sku المنتج"
                        value="{{ request('product_name') }}" />

                    <!-- بحث عن رقم الدفعة -->
                    <x-file-input id="batch_number" name="batch_number" label="رقم الدفعة"
                        value="{{ request('batch_number') }}" />

                     

                </div>
                <!-- زر البحث -->
                <div class="flex justify-end mt-2">
                    <x-button>
                        بحث
                    </x-button>
                </div>
            </div>
    </form>


    <section>
        <div class="flex items-center ">
            <x-title :title="'إدارة المنتجات في المستودعات'"></x-title>

            <x-button :href="route('inventory-products.create')" type="button" class="ml-4">
                <i class="fas fa-plus mr-2"></i> إضافة منتج جديد إلى مستودع
            </x-button>
            {{-- <div class="sm:col-span-6 flex justify-start mt-6">
                <x-button type="button" id="distribute-btn" data-type="1"> <i class="fas fa-plus mr-2"></i> توزيع منتج</x-button>
                <x-button type="button" id="withdraw-btn" data-type="-1"> <i class="fas fa-minus mr-2"></i> سحب منتج</x-button>
            </div> --}}
            
        </div>

        {{-- <p class="text-sm ">
            يرجى اختيار المستودع لعرض المنتجات المخزنة داخله.
        </p> --}}
        @if ($products->isEmpty())
            <p>لا توجد نتائج للبحث</p>
        @else
            <!-- عرض المنتجات في جدول -->
            @if ($products->isNotEmpty())
                <div class="overflow-x-auto mt-6">
                    <h2 class="text-lg font-semibold">المنتجات في المستودع المحدد</h2>
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="">
                                {{-- <th class="py-4">الرقم</th> --}}
                                <th class="py-4">النوع</th>

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
                                    {{-- <td class="">{{ $loop->iteration }}</td> --}}
                                    <td class="px-2 py-4">
                                        @if($product->distribution_type === 1)
                                            <i class="fas fa-arrow-up text-blue-500"></i> إدخال
                                        @elseif($product->distribution_type === -1)
                                            <i class="fas fa-arrow-down text-red-500"></i> إخراج
                                        @else
                                            <i class="fas fa-question-circle text-gray-500"></i> غير محدد
                                        @endif
                                    </td>
                                    
                                    
                                    <td class="px-6 py-4">
                                        <a href="{{ route('products.show', $product->product->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                            {{ $product->product->name }}
                                        </a>
                                    </td>
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
                                            'distribution_type'=>'1'
                                        ]) }}"
                                            class=" text-blue-500 hover:underline dark:text-white-500">
                                            <i class="fas fa-arrow-up ml-2"></i> <!-- توزيع عبر النقل -->
                                        </a>
                                        <a href="{{ route('inventory-products.createOut', [
                                            'warehouse_id' => $product->warehouse_id,
                                            'inventory_transaction_item_id' => $product->inventory_transaction_item_id,
                                            'product_id' => $product->product_id,
                                                                                        'distribution_type'=>'-1'

                                        ]) }}"
                                            class=" text-red-500 hover:underline dark:text-white-500">
                                            <i class="fas fa-arrow-down"></i> <!-- توزيع عبر النقل -->
                                        </a>
                                        <a href="{{ route('inventory-products.edit', $product->id) }}"
                                            class="text-blue-600 hover:underline dark:text-blue-500">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('inventory-products.destroy', $product->id) }}"
                                            method="POST" style="display: none;">
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

        @endif

    </section>

</x-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("warehouse_id");
        select.addEventListener("change", function() {
            this.form.submit();
        });
    });
document.addEventListener('DOMContentLoaded', function() {
    const distributeBtn = document.getElementById('distribute-btn');
    const withdrawBtn = document.getElementById('withdraw-btn');

    distributeBtn.addEventListener('click', function() {
        // إعادة التوجيه إلى صفحة إنشاء الحركة المخزنية مع النوع 1 (توزيع)
        window.location.href = `{{ route('inventory-products.create') }}?distribution_type=1`;
    });

    withdrawBtn.addEventListener('click', function() {
        // إعادة التوجيه إلى صفحة إنشاء الحركة المخزنية مع النوع -1 (سحب)
        window.location.href = `{{ route('inventory-products.createOut') }}?distribution_type=-1`;
    });
});
</script>
