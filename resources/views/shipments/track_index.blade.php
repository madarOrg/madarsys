<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'تتبع الشحنات'"></x-title>
        <div class="flex justify-center">
            <a href="{{ route('shipments.index') }}"
                class="w-52 h-12 flex items-center justify-center shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-white dark:text-gray-400 text-base font-semibold leading-7">

                العودة إلى قائمة الشحنات
            </a>
        </div>
    </section>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mt-4">
        <div class="mb-6">
            <form action="{{ route('shipments.track.index') }}" method="GET" class="flex space-x-4 space-x-reverse">
                <div class="w-1/3">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">بحث برقم الشحنة</label>
                    <input type="text" name="search" id="search"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        value="{{ request('search') }}">
                </div>
                <div class="w-1/3">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">تصفية حسب الحالة</label>
                    <select name="status" id="status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7">
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <h2 class="text-xl font-semibold mb-4">حالة جميع الشحنات</h2>

        @if ($shipments->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">لا توجد شحنات متطابقة مع معايير البحث.</span>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-400 text-white">
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الشحنة</th>
                            <th class="py-2 px-4 border-b">المنتج</th>
                            <th class="py-2 px-4 border-b">الكمية</th>
                            <th class="py-2 px-4 border-b">تاريخ الشحنة</th>
                            {{-- <th class="py-2 px-4 border-b">تاريخ الاستلام</th> --}}
                            <th class="py-2 px-4 border-b">الحالة</th>
                            {{-- <th class="py-2 px-4 border-b">تفاصيل</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr
                                class="{{ $shipment->status == 'received' ? 'bg-green-50' : ($shipment->status == 'shipped' ? 'bg-blue-50' : '') }}">
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->shipment_number }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->product->name }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->quantity }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->shipment_date }}</td>
                                {{-- <td class="py-2 px-4 border-b text-center">
                                    {{ $shipment->received_date ?? 'لم يتم الاستلام بعد' }}</td> --}}
                                <td class="py-2 px-4 border-b text-center">
                                    {{-- <span class="px-2 py-1 rounded-full text-xs font-bold
                                        {{ $shipment->status == 'received' ? 'bg-green-200 text-green-800' : '' }}
                                        {{ $shipment->status == 'shipped' ? 'bg-blue-200 text-blue-800' : '' }}
                                        {{ $shipment->status == 'confirmed' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                        {{ $shipment->status == 'pending' ? 'bg-gray-200 text-gray-800' : '' }}
                                    ">
                                        {{ $shipment->status == 'received' ? 'تم الاستلام' : '' }}
                                        {{ $shipment->status == 'shipped' ? 'تم الشحن' : '' }}
                                        {{ $shipment->status == 'confirmed' ? 'مؤكدة' : '' }}
                                        {{ $shipment->status == 'pending' ? 'قيد الانتظار' : '' }}
                                    </span> --}}
                                    @php
                                    switch ($shipment->status) {
                                        case 'pending':
                                            $badgeClass = 'bg-gray-200 text-gray-800';
                                            $statusText = 'قيد الانتظار';
                                            break;
                                        case 'shipped':
                                            $badgeClass = 'bg-blue-200 text-blue-800';
                                            $statusText = 'تم الشحن';
                                            break;
                                        case 'delivered':
                                            $badgeClass = 'bg-green-200 text-green-800';
                                            $statusText = 'تم التوصيل';
                                            break;
                                        default:
                                            $badgeClass = 'bg-red-200 text-red-800';
                                            $statusText = 'غير معروف';
                                    }
                                @endphp

                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>

                                </td>
                                {{-- <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('shipments.show', $shipment->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded">
                                        عرض
                                    </a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
