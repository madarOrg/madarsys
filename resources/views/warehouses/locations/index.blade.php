<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة مواقع المستودع'" />

        <!-- نموذج البحث مع تمرير معرف المستودع -->
        <form action="{{ route('warehouses.locations') }} " method="GET" class="ml-4">
            <x-search-input 
                id="custom-id" 
                name="search" 
                placeholder="ابحث عن مواقع المستودع" 
                :value="request()->input('search')" 
            />
        </form>
    </div>

    <!-- زر إضافة موقع جديد -->
    <x-button :href="route('warehouses.locations.create')" type="button" class="mb-4">
        <i class="fas fa-plus mr-2"></i> إضافة موقع مستودع جديد
    </x-button>
    

    <!-- جدول عرض مواقع المستودعات -->
    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">رقم الممر</th>
                <th class="px-6 py-3">رقم الرف</th>
                <th class="px-6 py-3">رقم الرف الفرعي</th>
                <th class="px-6 py-3">الموقع على الرف</th>
                <th class="px-6 py-3">الباركود</th>
                <th class="px-6 py-3">حالة الشغل</th>
                <th class="px-6 py-3">الملاحظات</th>
                <th class="px-6 py-3">تاريخ الإضافة</th>
                <th class="px-6 py-3">تاريخ آخر تحديث</th>
                <th class="px-6 py-3">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($warehouseLocations as $location)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <!-- رقم الصف -->
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ $location->aisle }}</td>
                    <td class="px-6 py-4">{{ $location->rack }}</td>
                    <td class="px-6 py-4">{{ $location->shelf }}</td>
                    <td class="px-6 py-4">{{ $location->position }}</td>
                    <td class="px-6 py-4">{{ $location->barcode }}</td>
                    <td class="px-6 py-4">
                        @if($location->is_occupied)
                            مشغول
                        @else
                            فارغ
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $location->notes }}</td>
                    <td class="px-6 py-4">{{ $location->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="px-6 py-4">{{ $location->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <!-- رابط تعديل الموقع -->
                        <a href="{{ route('warehouses.locations.edit', ['warehouse_location' => $location->id]) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        
                        <!-- نموذج حذف الموقع -->
                        <<form action="{{ route('warehouses.locations.destroy', ['warehouse_location' => $location->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        
                    </td>
                </tr>
            @empty
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="11" class="px-6 py-4 text-center">لا توجد بيانات للعرض</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layout>
    <x-pagination-links :paginator="$warehouses" />

