<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة المناطق التخزينية'"></x-title>
        <form action="{{ route('warehouse.storage-areas.index', ['warehouse' => $warehouse]) }}" method="GET">
            <x-search-input id="custom-id" name="search" placeholder="ابحث عن المناطق التخزينية" :value="request()->input('search')" />
        </form>
    </div>

    <!-- زر إضافة منطقة تخزينية جديدة -->
    <x-button :href="route('warehouse.storage-areas.create', $warehouse)" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة منطقة تخزينية جديدة
    </x-button>

    <!-- جدول المناطق التخزينية -->
    <table class="w-full table-fixed text-sm text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                {{-- <th class="p-4">
                    <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                </th> --}}
                <th class="px-6 py-3 ">اسم المنطقة التخزينية</th>
                <th class="px-6 py-3 ">نوع المنطقة التخزينية</th>
                <th class="px-6 py-3 ">السعة القصوى للتخزين</th>
                <th class="px-6 py-3 ">عدد المنتجات المخزنة</th>
                <th class="px-6 py-3 ">منطقة التخزين الجغرافية</th>
                <th class="px-6 py-3 ">شروط التخزين</th>
                <th class="px-6 py-3 ">تاريخ الإضافة</th>
                <th class="px-6 py-3 ">تاريخ آخر تحديث</th>
                <th class="px-6 py-3 ">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($storageAreas as $area)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    {{-- <td class="p-4">
                        <input type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                    </td> --}}
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                        <a href="javascript:void(0)"
                            onclick="toggleAreaDetails({{ $area->area_id }})">{{ $area->area_name }}</a>
                    </td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->area_type }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->capacity }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->current_occupancy }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->zone->name }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->storage_conditions }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $area->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td class="p-2 w-auto min-w-[50px] whitespace-nowrap flex space-x-2">
                        <a href="{{ route('warehouse.storage-areas.edit', [$warehouse, $area->id]) }}"
                            class="text-blue-600 hover:underline dark:text-blue-500">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('warehouse.storage-areas.destroy', [$warehouse, $area->id]) }}"
                            method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</x-layout>

<script>
    function toggleAreaDetails(areaId) {
        let detailsRow = document.getElementById(`area-details-${areaId}`);
        detailsRow.classList.toggle("hidden");
    }
</script>
