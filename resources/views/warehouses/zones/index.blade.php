<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة المناطق الجغرافية'"></x-title>

        <!-- نموذج البحث -->
        <form action="{{ route('warehouses.zones.index', ['warehouse' => $warehouse]) }}" method="GET">
            <x-search-input 
                id="custom-id" 
                name="search" 
                placeholder=" ابحث عن المناطق الجغرافية" 
                :value="request()->input('search')" 
            />
        </form>
    </div>

    <!-- زر إضافة منطقة جديدة -->
    <x-button :href="route('warehouses.zones.create', ['warehouse' => $warehouse])" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة منطقة جديدة
    </x-button>

    <!-- جدول المناطق -->
    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                
                <th class="px-6 py-3">اسم المنطقة</th>
                <th class="px-6 py-3">رمز المنطقة</th>
                <th class="px-6 py-3">وصف المنطقة</th>
                <th class="px-6 py-3">السعة الكلية</th>
                <th class="px-6 py-3">عدد الوحدات المخزنة </th>

                <th class="px-6 py-3">تاريخ الإضافة</th>
                <th class="px-6 py-3">تاريخ آخر تحديث</th>
                <th class="px-6 py-3">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($zones as $zone)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                  
                    
                    <td class="px-6 py-4">{{  $zone->name }}</td>
                    <td class="px-6 py-4">{{ $zone->code }}</td>
                    <td class="px-6 py-4">{{ $zone->description }}</td>
                    <td class="px-6 py-4">{{ $zone->capacity }}</td>
                    <td class="px-6 py-4">{{ $zone->current_occupancy }}</td>
                   
                    <td class="px-6 py-4">{{ $zone->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="px-6 py-4">{{ $zone->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <!-- تعديل -->
                        <a href="{{ route('warehouses.zones.edit', ['warehouse' => $warehouse, 'zone' => $zone->id]) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <!-- حذف -->
                        <form action="{{ route('warehouses.zones.destroy', ['warehouse' => $warehouse, 'zone' => $zone->id]) }}" method="POST" class="inline-block">
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
    <x-pagination-links :paginator="$zones" />
</x-layout>

