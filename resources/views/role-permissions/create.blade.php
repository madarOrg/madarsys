<x-layout>
    <section class="min-h-screen py-6">
        <form action="{{ route('role-permissions.store') }}" method="POST">
            @csrf
            <div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 shadow rounded">
                <!-- عنوان النموذج -->
                <div class="mb-6">
                    <x-title :title="'إضافة صلاحيات إلى دور'" />
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        يرجى اختيار الدور وتحديد الصلاحيات المناسبة مع الإعدادات.
                    </p>
                </div>

                <!-- اختيار الدور -->
                <div class="mb-6">
                    <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اختر الدور</label>
                    <select id="role_id" name="role_id" onchange="location.href='?role_id=' + this.value"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                        <option value="">-- اختر دوراً --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- جدول الصلاحيات -->
                @php
                    $existingPermissions = $rolePermissions ?? [];
                @endphp

                <div class="mb-6 overflow-auto">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الصلاحيات</label>
                    <table class="w-full table-auto border dark:border-gray-700 text-sm text-right">
                        <thead class="bg-gray-100 dark:bg-gray-700 dark:text-gray-300 text-gray-700">
                            <tr>
                                <th class="p-2 border">تفعيل</th>
                                <th class="p-2 border">اسم الصلاحية</th>
                                <th class="p-2 border">يمكن الإضافة</th>
                                <th class="p-2 border">يمكن التحديث</th>
                                <th class="p-2 border">يمكن الحذف</th>
                                {{-- <th class="p-2 border">الحالة</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 dark:text-gray-200">
                            @foreach ($permissions as $permission)
                                @php
                                    $existing = $existingPermissions[$permission->id] ?? null;
                                @endphp
                                <tr>
                                    <td class="p-2 border text-center">
                                        <input type="checkbox" name="permissions[{{ $permission->id }}][selected]" value="1"
                                            {{ $existing ? 'checked' : '' }}
                                            class="text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">
                                    </td>
                                    <td class="p-2 border">
                                        {{ $permission->moduleAction->name ?? '-' }}
                                    </td>
                                    <td class="p-2 border text-center">
                                        <input type="checkbox" name="permissions[{{ $permission->id }}][can_create]" value="1"
                                            {{ isset($existing['can_create']) && $existing['can_create'] ? 'checked' : '' }}
                                            class="text-green-600 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">
                                    </td>
                                    <td class="p-2 border text-center">
                                        <input type="checkbox" name="permissions[{{ $permission->id }}][can_update]" value="1"
                                            {{ isset($existing['can_update']) && $existing['can_update'] ? 'checked' : '' }}
                                            class="text-green-600 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">
                                    </td>
                                    <td class="p-2 border text-center">
                                        <input type="checkbox" name="permissions[{{ $permission->id }}][can_delete]" value="1"
                                            {{ isset($existing['can_delete']) && $existing['can_delete'] ? 'checked' : '' }}
                                            class="text-red-600 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">
                                    </td>
                                    {{-- <td class="p-2 border">
                                        <select name="permissions[{{ $permission->id }}][status]"
                                            class="w-full border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <option value="">-- اختر --</option>
                                            <option value="1" {{ isset($existing['status']) && $existing['status'] == 1 ? 'selected' : '' }}>مفعل</option>
                                            <option value="0" {{ isset($existing['status']) && $existing['status'] == 0 ? 'selected' : '' }}>غير مفعل</option>
                                        </select>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- زر الحفظ -->
                <div class="flex justify-end">
                    <x-button type="submit">حفظ الصلاحيات</x-button>
                </div>
            </div>
        </form>
    </section>
</x-layout>
