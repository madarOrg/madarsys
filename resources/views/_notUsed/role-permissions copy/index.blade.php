<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة الصلاحيات للأدوار'" />
        
        <!-- حقل البحث -->
        <form action="{{ route('role-permissions.index') }}" method="GET" class="flex items-center space-x-4">
            <x-search-input 
                id="role-permissions-id"
                name="search"
                placeholder="ابحث عن الصلاحيات"
                :value="request()->input('search')"
            />

            <!-- حقل البحث عن الدور -->
            <select name="role" class="px-4 py-2 border rounded">
                <option value="">اختر الدور</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request()->input('role') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <!-- حقل البحث عن الصلاحية -->
            <select name="permission" class="px-4 py-2 border rounded">
                <option value="">اختر الصلاحية</option>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}" {{ request()->input('permission') == $permission->id ? 'selected' : '' }}>
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>

            <!-- حقل البحث عن الحالة -->
            <select name="status" class="px-4 py-2 border rounded">
                <option value="">اختر الحالة</option>
                <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>فعال</option>
                <option value="0" {{ request()->input('status') == '0' ? 'selected' : '' }}>غير فعال</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">بحث</button>
        </form>
    </div>

    <!-- زر إضافة صلاحية جديدة -->
    <x-button :href="route('role-permissions.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة صلاحية جديدة
    </x-button>

    <!-- جدول عرض الصلاحيات المرتبطة بالأدوار -->
    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">الدور</th>
                <th class="px-6 py-3">الصلاحيات</th>
                <th class="px-6 py-3">الحالة</th>
                <th class="px-6 py-3">آخر تحديث للحالة</th>
                <th class="px-6 py-3">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rolePermissions as $role)
                @foreach($role->permissions as $permission)
                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">{{ $role->name }}</td>
                        <td class="px-6 py-4">{{ $permission->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold {{ $permission->pivot->status == 1 ? 'text-green-600 bg-green-200' : 'text-red-600 bg-red-200' }} rounded">
                                {{ $permission->pivot->status == 1 ? 'فعال' : 'غير فعال' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $permission->pivot->status_updated_at ? \Carbon\Carbon::parse($permission->pivot->status_updated_at)->format('Y-m-d H:i') : '-' }}
                        </td>
                        
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('role-permissions.edit', $role->id) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('role-permissions.destroy', $permission->pivot->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</x-layout>
