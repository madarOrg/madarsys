<x-layout dir="rtl">
    <x-title :title="'إدارة المستخدمين'"></x-title>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <!-- حقل البحث وزر الإضافة -->
        <div class="pb-4 bg-gray-50 dark:bg-gray-900">
            <label for="table-search" class="sr-only">بحث</label>
            <div class="relative mt-1 flex justify-start mb-4">
                <!-- أيقونة البحث -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-auto">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <!-- إدخال البحث -->
                <input type="text" id="table-search" class="block pr-10 text-sm text-gray-900 dark:text-gray-400 border rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ابحث عن المستخدمين">
                <!-- زر إضافة مستخدم جديد -->
                <x-button :href="route('users.create')" type="button">
                    <i class="fas fa-plus mr-2"></i> إضافة مستخدم جديد
                </x-button>
            </div>
        </div>
    
        <!-- جدول المستخدمين -->
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    {{-- <th class="p-4">
                        <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                    </th> --}}
                    <th class="px-6 py-3">اسم المستخدم</th>
                    <th class="px-6 py-3">البريد الإلكتروني</th>
                    <th class="px-6 py-3">رقم الهاتف</th>
                    <th class="px-6 py-3">الدور</th>
                    <th class="px-6 py-3">الحالة</th>
                    <th class="px-6 py-3">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    {{-- <td class="p-4">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                    </td> --}}
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ $user->phone_number }}</td>
                    <td class="px-6 py-4">{{ $user->role }}</td>
                    <td class="px-6 py-4">{{ $user->status }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
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
    </div>
</x-layout>
