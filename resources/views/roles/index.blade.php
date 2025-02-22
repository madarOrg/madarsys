<x-layout>
    <div class="relative mt-1 flex  md:flex-row items-center ">
        <x-title :title="'إدارة الأدوار'" />
        
        <div class="flex items-center space-x-2">
            <form action="{{ route('roles.index') }}" method="GET">
                <x-search-input 
                    id="roles-search"
                    name="search"
                    placeholder="ابحث عن الأدوار"
                    :value="request()->input('search')"
                />
            </form>
        </div>
        
    </div>
    <x-button :href="route('roles.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة دور جديد
    </x-button>
    
    <!-- جدول الأدوار -->
    <div class="overflow-x-auto mt-1">
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 border border-gray-300 rounded-lg">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">اسم الدور</th>
                    <th class="px-4 py-3">عدد المستخدمين</th>
                    <th class="px-4 py-3">آخر تحديث</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-300">{{ $role->name }}</td>
                    <td class="px-4 py-3 text-center">{{ $role->users->count() }}</td>
                    <td class="px-4 py-3">{{ $role->updated_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-white text-xs {{ $role->status ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $role->status ? 'فعال' : 'غير فعال' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex space-x-2">
                        <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="{{ route('roles.show', $role->id) }}" class="text-gray-600 hover:underline dark:text-gray-300">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline-block">
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
        <x-pagination-links :paginator="$roles" />
    </div>
</x-layout>



