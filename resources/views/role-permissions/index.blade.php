<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة الصلاحيات للأدوار'" />

        <!-- حقل البحث -->
        <form action="{{ route('role-permissions.index') }}" method="GET" class="flex items-center space-x-4">
            <x-search-input id="role-permissions-id" name="search" placeholder="ابحث عن الصلاحيات" :value="request()->input('search')" />

            <!-- حقل البحث عن الدور -->
            <div class="relative mt-1 flex items-center">
                <select name="role"
                    class="px-4 py-2 bg-gray-100 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200">
                    <option value="">اختر الدور</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ request()->input('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- حقل البحث عن الصلاحية -->
            {{-- <div class="relative mt-1 flex items-center">
                <select name="permission"
                    class="px-4 py-2 bg-gray-100 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200">
                    <option value="">اختر الصلاحية</option>
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->id }}"
                            {{ request()->input('permission') == $permission->id ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div> --}}


            <!-- حقل البحث عن الحالة -->
            {{-- <div class="relative mt-1 flex items-center">
                <select name="status"
                    class="px-4 py-2 bg-gray-100 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200">
                    <option value="">اختر الحالة</option>
                    <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>فعال</option>
                    <option value="0" {{ request()->input('status') == '0' ? 'selected' : '' }}>غير فعال</option>
                </select>
            </div> --}}


            <button type="submit" class="bg-blue-500 border dark:text-white text-gray-600 px-4 py-2 rounded">بحث</button>
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
                <th class="px-6 py-3">إمكانية التعديل</th>
                <th class="px-6 py-3">إمكانية الحذف</th>
                <th class="px-6 py-3">الحالة</th>
                <th class="px-6 py-3">آخر تحديث للحالة</th>
                {{-- <th class="px-6 py-3">الإجراء</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($rolePermissions as $role)
                @foreach ($role->permissions as $permission)
                    <tr
                        class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">{{ $role->name }}</td>
                        <td class="px-6 py-4">{{ $permission->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold {{ $permission->pivot->can_update ? 'text-green-600 bg-green-200' : 'text-red-600 bg-red-200' }} rounded">
                                {{ $permission->pivot->can_update ? 'نعم' : 'لا' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold {{ $permission->pivot->can_delete ? 'text-green-600 bg-green-200' : 'text-red-600 bg-red-200' }} rounded">
                                {{ $permission->pivot->can_delete ? 'نعم' : 'لا' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs font-semibold {{ $permission->pivot->status == 1 ? 'text-green-600 bg-green-200' : 'text-red-600 bg-red-200' }} rounded">
                                {{ $permission->pivot->status == 1 ? 'فعال' : 'غير فعال' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $permission->pivot->updated_at ? \Carbon\Carbon::parse($permission->pivot->updated_at)->format('Y-m-d H:i') : '-' }}
                        </td>

                        {{-- <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('role-permissions.edit', $role->id) }}"
                                class="text-blue-600 hover:underline dark:text-blue-500">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('role-permissions.destroy', $permission->pivot->id) }}"
                                method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td> --}}
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    {{-- <x-pagination-links :paginator="$rolePermissions" /> --}}


</x-layout>
@section('scripts')
    <script>
        document.querySelector('select[name="role"]').addEventListener('change', function() {
            this.form.submit();
        });

        // السماح بإسقاط العناصر داخل القائمة
        function allowDrop(event) {
            event.preventDefault();
        }

        // تنفيذ السحب
        document.querySelectorAll('.draggable').forEach(item => {
            item.addEventListener('dragstart', event => {
                event.dataTransfer.setData("text/plain", event.target.dataset.id);
            });
        });

        // تنفيذ الإسقاط في القائمة المحددة
        function drop(event, roleId) {
            event.preventDefault();
            let permissionId = event.dataTransfer.getData("text/plain");
            let draggedElement = document.querySelector(`.draggable[data-id='${permissionId}']`);

            let targetList = event.target.closest('.role-permissions');
            if (!targetList.contains(draggedElement)) {
                targetList.appendChild(draggedElement);
            }
        }

        // حفظ التعديلات
        function savePermissions() {
            let rolesData = [];

            document.querySelectorAll('.role-permissions').forEach(roleList => {
                let roleId = roleList.getAttribute('data-role-id');
                let permissions = Array.from(roleList.children).map(item => item.dataset.id);

                rolesData.push({
                    role_id: roleId,
                    permissions
                });
            });

            fetch("{{ route('role-permissions.update', ['role' => $role->id]) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        roles: rolesData
                    })
                })

                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error("Error:", error));
        }
    </script>
@endsection
