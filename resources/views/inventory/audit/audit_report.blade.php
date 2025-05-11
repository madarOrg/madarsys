<x-base>
    <div class="container ">
        <div class="hide-on-print text-right mt-2 mb-4">
            <button onclick="window.print()"
                class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                التقرير
            </button>
        </div>
        <x-reportHeader>
            <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300"> تقرير
                الجرد </h1>
        </x-reportHeader>

        @foreach ($transactions as $transaction)
            <h4 style="margin-top: 30px; font-size: 18px; color: #4B5563;">مستودع:
                {{ $transaction->warehouse->name ?? '-' }}</h4>

            <div class="overflow-x-auto mt-4">
                <table class="min-w-[800px] w-full border-collapse border border-gray-300 text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="border border-gray-300 p-2">اسم المنتج</th>
                            <th class="border border-gray-300 p-2">الباركود</th>
                            <th class="border border-gray-300 p-2">SKU</th>
                            <th class="border border-gray-300 p-2">الفئة</th>
                            <th class="border border-gray-300 p-2">الكمية المتوقعة</th>
                            <th class="border border-gray-300 p-2">الكمية الفعلية</th>
                            <th class="border border-gray-300 p-2">الفرق</th>
                          

                        </tr>
                    </thead>
                    <tbody>
                        @php
    $total_diff = 0;
@endphp

@foreach ($transaction->items as $item)
    @php
        $diff = $item->expected_audit_quantity - $item->quantity;
        $total_diff += $diff;
    @endphp

    <tr class="text-center">
        <td class="border border-gray-300 p-2">{{ $item->product->name ?? '-' }}</td>
        <td class="border border-gray-300 p-2">{{ $item->product->barcode ?? '-' }}</td>
        <td class="border border-gray-300 p-2">{{ $item->product->sku ?? '-' }}</td>
        <td class="border border-gray-300 p-2">{{ $item->product->Category->name ?? '-' }}</td>
        <td class="border border-gray-300 p-2">{{ $item->expected_audit_quantity }}</td>
        <td class="border border-gray-300 p-2">{{ $item->quantity }}</td>

      
        <td class="border border-gray-300 p-2 text-sm font-semibold text-center">
            @if ($diff > 0)
                <span class="text-green-500">{{ $diff }}  <i class="fas fa-arrow-up text-green-500 ml-1"></i></span>
            @elseif ($diff < 0)
                <span class="text-red-500">{{ abs($diff) }}  <i class="fas fa-arrow-down text-red-500 ml-1"></i></span>
            @else
                <span class="text-gray-500">-</span>
            @endif
        </td>
    </tr>
@endforeach


                       
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</x-base>
