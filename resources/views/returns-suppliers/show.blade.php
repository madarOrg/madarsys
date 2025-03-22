<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'تفاصيل المرتجع رقم ' . $returnOrder->return_number"></x-title>
   <!-- مربع البحث -->
   <form method="GET" action="{{ route('returns_process.index') }}">
            <x-search-input id="search-return-orders" name="search" placeholder="ابحث عن المرتجعات" :value="request()->input('search')" />
        </form>
    </section>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">#</th>
                    <th class="px-6 py-3">اسم المنتج</th>
                    <th class="px-6 py-3">الكمية</th>
                    <th class="px-6 py-3">الحالة</th>
                    <th class="px-6 py-3">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returnOrder->items as $item)
                    @php
                        // تحديد اللون بناءً على حالة المنتج
                        $statusColor = '';
                        switch ($item->status) {
                            case 'damaged':
                                $statusColor = 'bg-red-500'; // اللون الأحمر
                                break;
                            case 'expired':
                                $statusColor = 'bg-yellow-500'; // اللون الأصفر
                                break;
                            case 'wrong_item':
                                $statusColor = 'bg-blue-500'; // اللون الأزرق
                                break;
                            case 'restockable':
                                $statusColor = 'bg-green-500'; // اللون الأخضر
                                break;
                            default:
                                $statusColor = 'bg-gray-500'; // اللون الافتراضي
                        }
                    @endphp
                    <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <td>    <span class="inline-block {{ $statusColor }} w-4 h-4 rounded-full mr-2"></span></td>    
                        <td class="px-4 py-2">{{ $item->product->name }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 ">{{ $item->status }}</td>

                        <td class="px-4 py-2 w-1/4">
    <div class="flex flex-wrap gap-2">
        @if($item->status == 'damaged')
            <!-- زر تصنيف كمنتج تالف -->
            <x-button href="{{ route('returns_process.show', $item->id) }}" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                <i class="fas fa-exclamation-triangle text-xs"></i> تصنيف كمنتج تالف
            </x-button>
        @elseif($item->status == 'expired')
            <!-- زر إرجاع للمخزون -->
            <x-button href="{{ route('returns_process.show', $item->id) }}" class="bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <i class="fas fa-sync text-xs"></i> إرجاع للمخزون
            </x-button>
        @elseif($item->status == 'restockable')
            <!-- زر قبول الإرجاع -->
            <x-button href="{{ route('returns_process.show', $item->id) }}" class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                <i class="fas fa-check text-xs"></i> قبول الإرجاع
            </x-button>
        @else
            <!-- زر إرسال للصيانة -->
            <x-button href="{{ route('returns_process.show', $item->id) }}" class="bg-yellow-500 text-white p-2 rounded-full hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                <i class="fas fa-tools text-xs"></i> إرسال للصيانة
            </x-button>
        @endif
    </div>
</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-pagination-links :paginator="$items" />
    </x-layout>
