<x-layout dir="rtl">
    <div class="mt-4">
        <x-title :title="'تحديث صلاحيات الدور'" />

        <form method="GET" action="{{ route('role-permissions.edit') }}">
            <label for="role" class="block mb-2 font-semibold text-sm">اختر الدور:</label>
            <select name="role_id" id="role" onchange="this.form.submit()"
                class="w-full px-4 py-2 mb-4 bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                <option value="">-- اختر الدور --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </form>

        @if($selectedRole && $permissions)
        <form action="{{ route('role-permissions.update', $selectedRole->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="w-full mt-4 text-sm text-right text-gray-600 dark:text-gray-300">
                <thead class="text-xs bg-gray-400 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">الصلاحية</th>
                        <th class="px-4 py-2">يمكن التعديل</th>
                        <th class="px-4 py-2">يمكن الحذف</th>
                        <th class="px-4 py-2">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $permission->name }}</td>

                            <td class="px-4 py-2 text-center">
                                <input type="checkbox" name="permissions[{{ $permission->id }}][can_update]"
                                    {{ $permission->pivot->can_update ? 'checked' : '' }}>
                            </td>

                            <td class="px-4 py-2 text-center">
                                <input type="checkbox" name="permissions[{{ $permission->id }}][can_delete]"
                                    {{ $permission->pivot->can_delete ? 'checked' : '' }}>
                            </td>

                            <td class="px-4 py-2 text-center">
                                <select name="permissions[{{ $permission->id }}][status]"
                                    class="rounded px-2 py-1 bg-white dark:bg-gray-700">
                                    <option value="1" {{ $permission->pivot->status == 1 ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ $permission->pivot->status == 0 ? 'selected' : '' }}>غير فعال</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 text-left">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    حفظ التعديلات
                </button>
            </div>
        </form>
        @endif
    </div>
</x-layout>
