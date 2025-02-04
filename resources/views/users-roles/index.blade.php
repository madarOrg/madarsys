<x-layout>
    <div class="relative mt-1 flex flex-wrap items-center justify-between gap-4">
        <x-title :title="'إدارة أدوار المستخدمين'"></x-title>
        <x-search-input 
            id="roles-id"
            name="search"
            placeholder="ابحث عن أدوار المستخدمين"
            :value="request()->input('search')"
            class="w-full sm:w-auto"
        />
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">اسم المستخدم</th>
                    <th class="px-6 py-3">الأدوار الحالية</th>
                    <th class="px-6 py-3">إضافة دور</th>
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">{{ $user->name }}</td>
                        <td class="px-6 py-4 flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <div class="relative inline-flex items-center bg-blue-500 text-white rounded px-4 py-1 text-xs">
                                    <span>{{ $role->name }}</span>
                                    <form action="{{ route('users-roles.destroy', [$user->id, $role->id]) }}" method="POST" class="absolute top-0 left-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-700 hover:text-red-400 focus:outline-none text-lg">
                                            ×
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </td>

                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('users-roles.store') }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                            
                            
                                <select name="role_id" class="block w-full sm:w-auto text-sm text-gray-900 border rounded-lg bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                               
                                <x-button-secondary :href="route('users-roles.index')" type="submit">
                                    <i class="fas fa-plus mr-2"></i> إضافة أدوار جديدة
                                </x-button-secondary>
                            </form>
                        </td>

                        <td class="px-6 py-4 flex flex-wrap gap-2">
                            <!-- يمكن إضافة إجراءات إضافية هنا -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
