<x-layout>
    <div class="container">
        <x-title :title="'عرض قوائم الجرد'"></x-title>


        <form method="GET" action="{{ route('inventory.audit.warehouseReport') }}" class="mb-3">
            <div class="flex flex-wrap md:flex-nowrap gap-2 items-end w-full">
                {{-- <div class="col-md-3">
                                <label for="start_date">من تاريخ</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', $startDate) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">إلى تاريخ</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', $endDate) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="inventory_code">كود الجرد</label>
                                <input type="text" name="inventory_code" id="inventory_code" class="form-control" value="{{ request('inventory_code') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end"> --}}

                <!-- حقل "من تاريخ" -->

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
            
                <div class="hide-on-print  mb-4 mt-1">
                    <button type="submit" class=" btn btn-primary text-indigo-600 hover:text-indigo-700">تصفية</button>
                </div>
                
            </div>

    </div>

    </form>
    @foreach ($warehouseReports->groupBy('warehouse_id') as $warehouseId => $products)
        <h3 class="mt-4 text-xl font-semibold">{{ $products->first()->warehouse_name }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                    <th>المنتج</th>
                    @if ($groupByBatch)
                        <th>رقم الدفعة</th>
                    @endif
                    <th>إجمالي الكمية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td  class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->product_name }}</td>
                        @if ($groupByBatch)
                            <td  class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $product->batch_number }}</td>
                        @endif
                        <td  class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ number_format($product->total_quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    </div>
</x-layout>
