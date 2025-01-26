<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">

    <x-title :title="'إدارة المستودعات'"></x-title>

    <form method="GET" action="{{ route('warehouses.index') }}">
    <x-search-input 
    id="custom-id"
    name="search"
    placeholder="ابحث عن المستودعات"
    :value="request()->input('search')"
/>
</form>
</div>

    <!-- زر إضافة مستودع جديد -->
    <x-button :href="route('warehouses.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة مستودع جديد
    </x-button>
    
        <!-- جدول المستودعات -->
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">
                        <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                    </th>
                    <th class="px-6 py-3">اسم المستودع</th>
                    <th class="px-6 py-3">الشركة / الفرع</th>
                    <th class="px-6 py-3">العنوان</th>
                    <th class="px-6 py-3">القدرة</th>
                    <th class="px-6 py-3">عدد الرفوف</th>
                    <th class="px-6 py-3">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($warehouses as $warehouse)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                        <a href="javascript:void(0)" onclick="toggleWarehouseDetails({{ $warehouse->id }})">{{ $warehouse->name }}</a>
                    </td>
                    <td class="px-6 py-4">
                        {{ $warehouse->branch->company->name ?? 'لا يوجد' }} / 
                        {{ $warehouse->branch->name ?? 'لا يوجد' }}
                    </td>
                    <td class="px-6 py-4">{{ $warehouse->address }}</td>
                    <td class="px-6 py-4">{{ $warehouse->capacity }} متر مربع</td>
                    <td class="px-6 py-4">{{ $warehouse->shelves_count }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- تفاصيل المستودع -->
                <tr id="warehouse-details-{{ $warehouse->id }}" class="hidden">
                    <td colspan="7" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <x-title :title="'تفاصيل المستودع: ' . $warehouse->name" />

                        <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-600 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">المشرف</th>
                                    <th class="px-6 py-3">الأنظمة الذكية</th>
                                    <th class="px-6 py-3">نظام الأمان</th>
                                    <th class="px-6 py-3">درجة الحرارة</th>
                                    <th class="px-6 py-3">الرطوبة</th>
                                    <th class="px-6 py-3">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $warehouse->supervisor->name ?? 'لا يوجد' }}</td>
                                    <td class="px-6 py-4">{{ $warehouse->is_smart ? 'نعم' : 'لا' }}</td>
                                    <td class="px-6 py-4">{{ $warehouse->has_security_system ? 'نعم' : 'لا' }}</td>
                                    <td class="px-6 py-4">{{ $warehouse->temperature }} °C</td>
                                    <td class="px-6 py-4">{{ $warehouse->humidity }} %</td>
                                    <td class="px-6 py-4">
                                        <!-- إضافة إجراءات للمستودع -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function toggleWarehouseDetails(warehouseId) {
            let detailsRow = document.getElementById(`warehouse-details-${warehouseId}`);
            detailsRow.classList.toggle("hidden");
        }
    </script>
</x-layout>
