<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إدارة الشحنات'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('shipments.index') }}">
            <x-search-input id="search-shipments" name="search" placeholder="ابحث عن الشحنات" :value="request()->input('search')" />
        </form>
        <!-- زر إضافة شحنة جديدة -->
        <x-button :href="route('shipments.create')" type="button" class="mb-4">
            <i class="fas fa-plus mr-2"></i> إضافة شحنة جديدة
        </x-button>
    </section>

    <!-- جدول عرض الشحنات -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">رقم الشحنة</th>
                    <th class="px-6 py-3">الشركة\الفرع</th>
                    <th class="px-6 py-3">حالة الشحنة</th>
                    <th class="px-6 py-3">تاريخ الشحنة</th>
                    <th class="px-6 py-3">تاريخ تسليم الشحنة</th>
                    <th class="px-6 py-3">شركة الشحنة</th>
                    <th class="px-6 py-3">عنوان الشحنة</th>
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shipments as $shipment)
                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="p-4">{{ $shipment->tracking_number }}</td>
                        <td class="px-6 py-4">{{ $shipment->recipient_name }}</td>
                        <td class="px-6 py-4">{{ $shipment->address }}</td>
                        <td class="px-6 py-4">{{ $shipment->shipment_date }}</td>
                        <td class="px-6 py-4">{{ $shipment->status }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- زر التعديل -->
                            <x-button href="{{ route('shipments.edit', $shipment->id) }}" class="text-yellow-600 hover:underline">
                                <i class="fas fa-pen"></i>
                            </x-button>

                            <!-- زر الحذف -->
                            <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('هل أنت متأكد من حذف هذه الشحنة؟')">
                                    <i class="fas fa-trash-alt"></i>
                                </x-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>

