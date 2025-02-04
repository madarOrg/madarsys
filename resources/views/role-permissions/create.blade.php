<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900 min-h-screen py-6">
        <form action="{{ route('role-permissions.store') }}" method="POST">
            @csrf
            <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow rounded">
                <!-- عنوان النموذج والوصف -->
                <div class="mb-6">
                    <x-title :title="'إضافة صلاحيات إلى دور'" />
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        يرجى اختيار الدور وسحب الصلاحيات المناسبة إليه.
                    </p>
                </div>

                <!-- اختيار الدور -->
                <div class="mb-6">
                    <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اختر الدور</label>
                    <select id="role_id" name="role_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="">-- اختر دوراً --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- منطقة السحب والإفلات في عمودين -->
                <div class="mb-6 grid grid-cols-2 gap-6">
                    <!-- عمود الصلاحيات المتاحة -->
                    <div>
                        <h3 class="text-sm  font-medium text-gray-900 dark:text-gray-300 mb-2">الصلاحيات المتاحة</h3>
                        <div id="available-permissions" class="border p-4 bg-white dark:bg-gray-700 rounded-md min-h-[150px]">
                            @foreach($permissions as $permission)
                                <div class="draggable cursor-move px-3 py-2 bg-gray-200 dark:bg-gray-600 rounded-md mb-2" draggable="true" data-id="{{ $permission->id }}">
                                    {{ $permission->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- عمود الصلاحيات المختارة -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الصلاحيات المختارة للدور</h3>
                        <div id="selected-permissions" class="border p-4 bg-gray-100 dark:bg-gray-600 rounded-md min-h-[150px]" ondragover="allowDrop(event)" ondrop="drop(event)">
                            <!-- سيتم وضع الصلاحيات هنا عند السحب -->
                        </div>
                    </div>
                </div>

                <!-- زر الإرسال -->
                <div class="flex justify-end">
                    <x-button type="submit">حفظ الصلاحيات</x-button>
                </div>
            </div>
        </form>
    </section>

    <!-- سكربت السحب والإفلات -->
    <script>
        // السماح بالسحب على منطقة الإسقاط
        function allowDrop(event) {
            event.preventDefault();
        }

        // تهيئة جميع العناصر القابلة للسحب
        document.querySelectorAll('.draggable').forEach(item => {
            item.addEventListener('dragstart', event => {
                // استخدام نوع MIME "text/plain" لتخزين معرف الصلاحية
                event.dataTransfer.setData("text/plain", event.target.dataset.id);
            });
        });

        // دالة عند إسقاط عنصر في منطقة الصلاحيات المختارة
        function drop(event) {
            event.preventDefault();
            let permissionId = event.dataTransfer.getData("text/plain");
            let draggedElement = document.querySelector(`.draggable[data-id='${permissionId}']`);
            let targetList = document.getElementById("selected-permissions");

            // نقل العنصر إلى قائمة الصلاحيات المختارة إذا لم يكن موجوداً بالفعل
            if (draggedElement && !targetList.contains(draggedElement)) {
                targetList.appendChild(draggedElement);
            }
        }
    </script>
</x-layout>
