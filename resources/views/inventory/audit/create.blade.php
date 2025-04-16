<x-layout>
    <div class="container mx-auto p-6  shadow-lg rounded-lg">
        {{-- <h1 class="text-3xl font-bold mb-8 text-center">إضافة عملية جرد جديدة</h1> --}}
        <x-title :title="' إضافة عملية جرد جديدة'"></x-title>
        <form method="POST" action="{{ route('inventory.audit.store') }}" id="auditForm">
            @csrf

            <!-- 1. بيانات الجرد الأساسية -->
            <div class=" p-6 rounded-lg shadow mb-6">
                <h2 class="text-xl font-semibold mb-4">بيانات الجرد الأساسية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="mb-2">
                        <!-- كود الجرد -->
                        <x-file-input label="كود الجرد" id="inventory_code" name="inventory_code" type="text"
                            value="{{ old('inventory_code', 'AUTO_GENERATED_CODE') }}" readonly />
                    </div>
                    {{-- <div>
                       
                        <label for="inventory_code" class="block ">كود الجرد</label>
                        <input type="text" name="inventory_code" id="inventory_code"
                            class="form-input mt-1 block w-full"
                            value="{{ old('inventory_code', 'AUTO_GENERATED_CODE') }}" readonly>
                    </div> --}}
                    <!-- نوع الجرد -->

                    <!-- الأنواع الفرعية لعنصر النوع 8 -->
                    @if ($subTypes->isNotEmpty())
                        <div class="mb-4">
                            <label for="inventory_type" class="block">الأنواع الفرعية</label>
                            <select name="inventory_type" id="inventory_type" class="form-select mt-1 block w-full">
                                @foreach ($subTypes as $subType)
                                    <option value="{{ $subType->id }}">{{ $subType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <!-- تاريخ بدء الجرد -->
                    {{-- <div>
                       
                        <label for="start_date" class="block ">تاريخ بدء الجرد</label>
                        <input type="datetime-local" name="start_date" id="start_date"
                            class="form-input mt-1 block w-full">
                    </div> --}}
                    <!-- تاريخ انتهاء الجرد -->
                    {{-- <div>
                        <label for="end_date" class="block ">تاريخ انتهاء الجرد</label>
                        <input type="datetime-local" name="end_date" id="end_date"
                            class="form-input mt-1 block w-full">
                    </div> --}}
                    <!-- الملاحظات -->
                    {{-- <div class="md:col-span-2">
                        <label for="notes" class="block ">ملاحظات</label>
                        <textarea name="notes" id="notes" rows="3" class="form-textarea mt-1 block w-full"></textarea>
                    </div> --}}
                    <div class="">
                        <!-- تاريخ بدء الجرد -->
                        <x-file-input label="تاريخ بدء الجرد" id="start_date" name="start_date" type="datetime-local" />
                    </div>

                    <div class="">
                        <!-- تاريخ انتهاء الجرد -->
                        <x-file-input label="تاريخ انتهاء الجرد" id="end_date" name="end_date" type="datetime-local" />
                    </div>
                    <div class="">
                        <!-- الملاحظات -->
                        <x-textarea label="ملاحظات" id="notes" name="notes" rows="3" />
                    </div>

                </div>
            </div>

            <!-- 2. اختيار المستخدمين وتحديد صلاحياتهم -->
            {{-- <div class="bg-gray-50 p-6 rounded-lg shadow mb-6">
                <h2 class="text-2xl font-semibold mb-4">اختيار المستخدمين المسؤولين وتحديد صلاحياتهم</h2>
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- قائمة المستخدمين المتاحين -->
                    <div class="w-full md:w-1/2">
                        <label class="block text-gray-700 mb-2">المستخدمون المتاحون</label>
                        <select id="available_users" class="form-select w-full" multiple size="10">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- أزرار النقل -->
                    <div class="flex flex-col justify-center gap-2">
                        <button type="button" id="btn_add" class="w-52 h-12 shadow-sm rounded-lg  hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7">إضافة</button>
                        <button type="button" id="btn_remove" class="w-52 h-12 shadow-sm rounded-lg  hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7">حذف</button>
                    </div>
                    <!-- قائمة المستخدمين المختارين -->
                    <div class="w-full md:w-1/2">
                        <label class="block text-gray-700 mb-2">المستخدمون المختارون</label>
                        <select id="selected_users" name="users[]" class="form-select w-full" multiple size="10">
                            <!-- العناصر المضافة هنا عبر النقل -->
                        </select>
                    </div>
                </div> --}}


            <div class=" p-6 rounded-lg shadow mb-6">
                <h2 class="text-xl font-semibold mb-4">اختيار المستخدمين المسؤولين وتحديد صلاحياتهم</h2>
                <div class="flex  justify-center gap-2">
                    <!-- قائمة المستخدمين المتاحين -->
                    <div class="w-1/2">
                        <label class="block  mb-2">المستخدمون المتاحون</label>
                        <select id="available_users"
                            class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1 "
                            multiple size="10">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- أزرار النقل -->
                    <div class="flex flex-col justify-center gap-2">
                        <button type="button" id="btn_add"
                            class="w-32 h-12 shadow-sm rounded-lg  text-gray-700 dark:text-gray-800 transition-all duration-700  text-base font-semibold leading-7"><i
                                class="fa-solid fa-angles-left"></i></button>
                        <button type="button" id="btn_remove"
                            class="w-32 h-12 shadow-sm rounded-lg  text-gray-700 dark:text-gray-800transition-all duration-700  text-base font-semibold leading-7"><i
                                class="fa-solid fa-angles-right"></i></button>
                    </div>

                    <!-- قائمة المستخدمين المختارين -->
                    <div class="w-1/2">
                        <label class="block  mb-2">المستخدمون المختارون</label>
                        <select id="selected_users" name="users[]"
                            class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1 "
                            multiple size="10">
                            <!-- العناصر المضافة هنا عبر النقل -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- قسم تحديد صلاحيات المستخدمين -->
            <div id="user-permissions" class="mt-4 hidden">
                <h3 class="text-xl font-medium">تحديد صلاحيات المستخدمين المختارين</h3>
                <div id="permissions-container">
                    <!-- تُضاف هنا حقول تحديد الصلاحيات -->
                </div>
            </div>

            <!-- 3. اختيار المستودعات -->
            <div class=" p-6 rounded-lg shadow mb-6">
                <h2 class="text-xl font-semibold mb-4">اختيار المستودعات المستهدفة</h2>
                <div class="flex  justify-center gap-2">
                    <!-- قائمة المستودعات المتاحة -->
                    <div class="w-1/2">
                        <label class="block  mb-2">المستودعات المتاحة</label>
                        <select id="available_warehouses"
                            class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1 "
                            multiple size="10">
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- أزرار النقل -->
                    <div class="flex flex-col justify-center gap-2">
                        <button type="button" id="btn_add_wh"
                            class="w-32 h-12 shadow-sm rounded-lg text-gray-700 dark:text-gray-800 transition-all duration-700  text-base font-semibold leading-7"><i
                                class="fa-solid fa-angles-left"></i></button>
                        <button type="button" id="btn_remove_wh"
                            class="w-32 h-12 shadow-sm rounded-lg  text-gray-700 dark:text-gray-800 transition-all duration-700 text-base font-semibold leading-7"><i
                                class="fa-solid fa-angles-right"></i></button>
                    </div>
                    <!-- قائمة المستودعات المختارة -->
                    <div class="w-1/2"> <label class="block mb-2">المستودعات المختارة</label>
                        <select id="selected_warehouses" name="warehouses[]"
                            class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1 "
                            multiple size="10">

                            <!-- العناصر المضافة هنا عبر النقل -->
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-button type="submit">حفظ
                    العملية</x-button>
            </div>
    </div>

    </form>
    </div>

    <script>
        // دالة لنقل العناصر المحددة بين القوائم
        function moveSelected(sourceSelect, targetSelect) {
            const selectedOptions = Array.from(sourceSelect.selectedOptions);
            selectedOptions.forEach(option => {
                targetSelect.appendChild(option);
            });
            updatePermissions();
        }

        // تحديث قسم صلاحيات المستخدمين بناءً على القائمة المختارة
        function updatePermissions() {
            const selectedUserIds = Array.from(document.getElementById('selected_users').options).map(option => option
                .value);
            const permissionsContainer = document.getElementById('permissions-container');
            const userPermissionsSection = document.getElementById('user-permissions');

            if (selectedUserIds.length > 0) {
                userPermissionsSection.classList.remove('hidden');
                permissionsContainer.innerHTML = '';
                selectedUserIds.forEach(userId => {
                    permissionsContainer.innerHTML += `
                        <div class="mt-4">
                            <label for="user_permission_${userId}" class="block text-gray-700">صلاحيات المستخدم (ID: ${userId})</label>
                            <select name="user_permissions[${userId}][]" id="user_permission_${userId}" class="form-select mt-1 block w-full">
                                <option value="1">جرد</option>
                                <option value="2">تسوية</option>
                                <option value="0">كلاهما</option>
                            </select>
                        </div>`;
                });
            } else {
                userPermissionsSection.classList.add('hidden');
            }
            updateHiddenUsers();
        }

        // تحديث الحقول المخفية لإرسال بيانات المستخدمين والمستودعات
        function updateHiddenUsers() {
            const userIds = Array.from(document.getElementById('selected_users').options).map(option => option.value);
            document.getElementById('selected_users_hidden').value = JSON.stringify(userIds);
        }

        function updateHiddenWarehouses() {
            const warehouseIds = Array.from(document.getElementById('selected_warehouses').options).map(option => option
                .value);
            document.getElementById('selected_warehouses_hidden').value = JSON.stringify(warehouseIds);
        }

        // تهيئة أزرار النقل للمستخدمين
        document.getElementById('btn_add').addEventListener('click', function() {
            moveSelected(document.getElementById('available_users'), document.getElementById('selected_users'));
        });
        document.getElementById('btn_remove').addEventListener('click', function() {
            moveSelected(document.getElementById('selected_users'), document.getElementById('available_users'));
        });

        // تهيئة أزرار النقل للمستودعات
        document.getElementById('btn_add_wh').addEventListener('click', function() {
            moveSelected(document.getElementById('available_warehouses'), document.getElementById(
                'selected_warehouses'));
        });
        document.getElementById('btn_remove_wh').addEventListener('click', function() {
            moveSelected(document.getElementById('selected_warehouses'), document.getElementById(
                'available_warehouses'));
        });

        // عند تقديم النموذج، تحديث الحقول المخفية
        document.getElementById('auditForm').addEventListener('submit', function() {
            updateHiddenUsers();
            updateHiddenWarehouses();
        });
    </script>

    <!-- حقول مخفية لإرسال البيانات بصيغة JSON -->
    <input type="hidden" name="selected_users" id="selected_users_hidden">
    <input type="hidden" name="selected_warehouses" id="selected_warehouses_hidden">
</x-layout>
