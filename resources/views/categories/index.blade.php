<x-layout>
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة الفئات'" />
        <form method="GET" action="{{ route('categories.index') }}">
            <x-search-input 
                id="custom-id"
                name="search"
                placeholder="ابحث عن الفئات"
                :value="request()->input('search')"
            />
        </form>
    </div>

    <!-- زر إضافة فئة جديدة -->
    <x-button :href="route('categories.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة فئة جديدة
    </x-button>
    
    <!-- جدول الفئات -->
    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">اسم الفئة</th>
                <th class="px-6 py-3">الوصف</th>
                <th class="px-6 py-3">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                    {{ $category->name }}
                </td>
                <td class="px-6 py-4">{{ $category->description ?? 'لا يوجد' }}</td>
                <td class="px-6 py-4 flex space-x-2">
                    <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block">
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
    <x-pagination-links :paginator=" $categories" />
</x-layout>
