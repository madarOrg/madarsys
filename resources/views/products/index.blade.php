<x-layout>
    <section>
        <div class="relative mt-2 flex items-center">
            <x-title :title="'جميع المنتجات'"></x-title>
                {{-- <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
             هنا يتم ترميز المنتجات 
                 </p> --}}
                 <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                    <x-search-input id="custom-id" name="search" placeholder="ابحث عن المنتجات" :value="request()->input('search')" />
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">بحث</button>
                </form>
                                
        </div>

            <!-- زر إضافة منتج جديد -->
            <x-button :href="route('products.create')" type="button">
                <i class="fas fa-plus mr-2"></i> إضافة منتج جديد
            </x-button>

                <div class="flex justify-between items-center mb-2">

                    <!-- الجدول -->
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">الصورة</th>
                                <th class="px-6 py-3">اسم المنتج</th>
                                <th class="px-6 py-3"> كود التخزين SKU</th>
                                <th class="px-6 py-3" > الباركود</th>

                                <th class="px-6 py-3">الفئة</th>
                                <th class="px-6 py-3">المورد</th>
                                {{-- <th class="px-6 py-3">سعر الشراء</th> --}}
                                {{-- <th class="px-6 py-3">سعر البيع</th> --}}
                                <th class="px-6 py-3">المخزون</th>
                                <th class="px-6 py-3">الحالة</th>
                                {{-- <th class="px-6 py-3">تاريخ الشراء</th>
                                <th class="px-6 py-3">تاريخ التصنيع</th>
                                <th class="px-6 py-3">تاريخ الانتهاء</th> --}}
                                {{-- <th class="px-6 py-3"> تاريخ احر تحديث </th>
                                <th class="px-6 py-3">التخفيضات (%)</th>
                                <th class="px-6 py-3">الضريبة (%)</th> --}}
                                <th class="px-6 py-3">الحد الأدنى للطلب</th>
                                <th class="px-6 py-3">الحد الأعلى للطلب</th>

                                {{-- <th class="px-6 py-3">العلامة التجارية</th>
                                <th class="px-6 py-3">الوحدة</th> --}}
                                
                                <th class="px-6 py-3">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="p-4">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="w-16 md:w-24 rounded-md object-cover" alt="{{ $product->name }}">
                                    </td>
                                    <td class="px-6 py-4 "> {{ $product->name }}</td>
                                    <td class="px-6 py-4 "> {{ $product->sku }}</td>
                                    <td class="px-6 py-4 "> {{ $product->barcode }}</td>

                                    <td class="px-6 py-4">{{ $product->category->name }}</td>
                                    <td class="px-6 py-4">{{ optional($product->supplier)->name ?? 'غير متوفر' }}</td>
                                    {{-- <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($product->purchase_price, 2) }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($product->selling_price, 2) }}</td> --}}
                                    <td class="px-6 py-4">{{ $product->stock_quantity }} {{ $product->unit_id }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-md {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $product->is_active ? 'متاح' : 'غير متاح' }}
                                        </span>
                                    </td>
                                    {{-- <td class="px-6 py-4">{{ $product->purchase_date }}</td>
                                    <td class="px-6 py-4">{{ $product->manufacturing_date }}</td>
                                    <td class="px-6 py-4">{{ $product->expiration_date }}</td>
                                    <td class="px-6 py-4">{{ $product->updated_at }}</td> --}}
                                    {{-- <td class="px-6 py-4">{{ $product->discount }}%</td>
                                    <td class="px-6 py-4">{{ $product->tax }}%</td> --}}
                                    <td class="px-6 py-4">{{ $product->min_stock_level }}</td>
                                    <td class="px-6 py-4">{{ $product->max_stock_level }}</td>

                                    {{-- <td class="px-6 py-4">{{ $product->brand }}</td>
                                    <td class="px-6 py-4">{{ $product->unit->name }}</td> --}}

                                    
                                    <td class="px-6 py-4">
                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"> <i
                                                class="fas fa-eye"></i></a>
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="text-blue-600 hover:underline dark:text-blue-500">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('products.destroy', $product->id) }}" method="POST"
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

                </div>
            
            <x-pagination-links :paginator="$products" />
    </section>
</x-layout>
