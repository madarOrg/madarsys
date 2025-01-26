<x-layout>
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة أدوار المستخدمين'"></x-title>
    </div>
    
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="pb-4 bg-gray-50 dark:bg-gray-900">
            <label for="table-search" class="sr-only">بحث</label>
            <div class="relative mt-1 flex justify-start mb-4">
                <input type="text" id="table-search" class="block pr-10 text-sm text-gray-900 dark:text-gray-400 border rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ابحث عن المستخدمين">
            </div>
        </div>

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
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <div class="relative inline-block bg-blue-500 text-white rounded px-2 py-1 text-xs">
                                    <span>{{ $role->name }}</span>
                                    <form action="{{ route('users-roles.destroy', [$user->id, $role->id]) }}" method="POST" class="absolute top-0 left-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-white hover:text-red-300 focus:outline-none" style="font-size: 10px;">
                                            ×
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </td>

                        <td class="px-6 py-4">
                            <form action="{{ route('users-roles.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <select name="role_id" class="block text-sm text-gray-900 border rounded-lg bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="mt-2 text-white bg-green-600 hover:bg-green-700 px-4 py-1 rounded-lg">
                                    <i class="fas fa-plus"></i> إضافة
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            @foreach($user->roles as $role)
                                <form action="{{ route('users-roles.destroy', [$user->id, $role->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash-alt"></i> حذف
                                    </button>
                                </form>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
