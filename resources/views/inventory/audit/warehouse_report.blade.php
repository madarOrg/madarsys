<x-layout>
    <div class="container">
       


        <form method="GET" action="{{ route('inventory.audit.warehouseReport') }}" class="mb-3">
            <div x-data="{ open: true }">
                <!-- زر لفتح أو إغلاق القسم -->
                <button type="button" @click="open = !open" class="hide-on-print text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                    <span
                        x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                    </span>
                </button>

                   <!-- الحقول القابلة للطي -->
            <div x-show="open" x-transition>

            <div class=" flex flex-wrap md:flex-nowrap gap-2 items-end w-full">
            
            <div class="flex-1 min-w-[250px]">
                    <x-file-input label="من تاريخ" id="start_date" name="start_date" type="date"
                        value="{{ request('start_date', $startDate) }}" />
                </div>

                <!-- حقل "إلى تاريخ" -->
                <div class="flex-1 min-w-[200px]">
                    <x-file-input label="إلى تاريخ" id="end_date" name="end_date" type="date"
                        value="{{ request('end_date', $endDate) }}" />
                </div>

                <!-- حقل "كود الجرد" -->
                <div class="flex-1 min-w-[200px]">
                    <x-file-input label="كود الجرد" id="inventory_code" name="inventory_code" type="text"
                        value="{{ request('inventory_code') }}" />
                </div>
            </div>

            <div class="form-check mt-2">
                <x-checkbox 
                id="group_by_batch" 
                name="group_by_batch" 
                :checked="request('group_by_batch')" 
                label="تجميع حسب الدفعة" 
            />
            
                
            </div>
            
            <div class="hide-on-print  mb-4 mt-1">
                <button type="submit" class=" btn btn-primary text-indigo-600 hover:text-indigo-700">تصفية</button>
            </div>
           
  </div>
    </div>

    </form>
    <div class="container mx-auto p-4">
        <!-- زر الطباعة - يظهر فقط عند العرض العادي -->
        <div class="hide-on-print text-right mb-4">
            <button onclick="window.print()"
                class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                 التقرير
            </button>
        </div>
         <!-- رأس التقرير -->
         <x-reportHeader>
            <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300"> عرض قوائم الجرد</h1>
        </x-reportHeader>

    <div class=" flex items-center space-x-2">
    @foreach ($warehouseReports->groupBy('warehouse_id') as $warehouseId => $products)
        <h3 class="dark:text-gray-100 text-xl font-semibold"> ({{ $products->first()->warehouse_name }} )</h3>
    </div>
        <div class="">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                  
                    <th  class="border p-2">المنتج</th>
                    @if ($groupByBatch)
                        <th  class="border p-2">رقم الدفعة</th>
                    
                    <th  class="border p-2">المنطقة التخزينية </th>
                    <th  class="border p-2">موقع المنتج</th>
                    @endif
                    <th  class="border p-2">إجمالي الكمية</th>
                </tr>
            </thead>
            <tbody class=" p-2 w-auto min-w-[50px] whitespace-nowrap">
                @foreach ($products as $product)
                <tr>
                    <td class="border p-2"><a href="{{ route('products.show', $product->product_id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        {{ $product->product_name }}  {{ $product->sku }}
                    </a>
                </td>
                        @if ($groupByBatch)
                            <td  class="border p-2">{{ $product->batch_number }}</td>
                       
                        <td  class="border p-2"> {{ $product->rack_code }}</td>
                        <td  class="border p-2"> {{ $product->area_name }}</td>
                        @endif 
                        <td  class="border p-2">{{ number_format($product->total_quantity, 2) }}</td>
                        

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    </div>
</x-layout>
