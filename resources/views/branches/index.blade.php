<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">

        <x-title :title="'إدارة جميع الفروع'" />
    
        <form method="GET" action="{{ route('branches.index') }}">
        <x-search-input 
        id="custom-id"
        name="search"
        placeholder="ابحث عن الفروع"
        :value="request()->input('search')"
    />
    </form>
</div>
 <!-- زر إضافة فرع جديد -->
 <x-button :href="route('branches.create')" type="button">
    <i class="fas fa-plus mr-2"></i> إضافة فرع جديد
</x-button>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-gray-500 dark:text-gray-400 mb-4">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-600 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">اسم الفرع</th>
                    <th class="px-6 py-3">العنوان</th>
                    <th class="px-6 py-3">رقم الهاتف</th>
                    <th class="px-6 py-3">اسم الشركة</th>
                    <th class="px-6 py-3">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branch)
                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $branch->name }}</td>
                        <td class="px-6 py-4">{{ $branch->address }}</td>
                        <td class="px-6 py-4">{{ $branch->phone_number }}</td>
                        <td class="px-6 py-4">{{ $branch->company->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('branches.edit', $branch->id) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">لا توجد فروع لعرضها.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
