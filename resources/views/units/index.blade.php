<x-layout>
    <div class="container">
        <x-title :title="'الوحدات '" class="form-title" />
        <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">
            هنا يمكنك إضافة وحدة جديدة إلى النظام. تأكد من تحديد اسم الوحدة، الوحدة الأب، ومعامل التحويل، ليتمكن النظام من استخدام هذه الوحدة في حسابات المنتجات وإدارة المخزون بشكل دقيق.
        </p>
        
        <form method="POST" id="unit-form" action="{{ route('units.store') }}">
            @csrf
            <div x-data="{ open: true }">
                <!-- زر لفتح أو إغلاق القسم -->
                <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                    <span
                        x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                    </span>
                </button>


                <!-- الحقول القابلة للطي -->
                <div x-show="open" x-transition>
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="unit_id" id="unit_id">
                    <div class="flex space-x-4 gap-4">
                        <div class="form-group w-1/3">
                            <label for="name"
                                class="block text-sm font-medium text-gray-600 dark:text-gray-400">الاسم</label>
                            <input type="text" name="name" id="name"
                                class="form-control w-full bg-gray-100 rounded border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 mt-1"
                                required>
                        </div>

                        <div class="form-group w-1/3">
                            <label
                                for="parent_unit_id"class="block text-sm font-medium text-gray-600 dark:text-gray-400">الوحدة
                                الأب</label>
                            <select name="parent_unit_id" id="parent_unit_id"
                                class="form-control w-full bg-gray-100 rounded border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 mt-1 ">
                                <option value="">-- لا شيء --</option>
                                @foreach ($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group w-1/3 mt-1">
                            <label for="conversion_factor"
                                class="block text-sm font-medium text-gray-600 dark:text-gray-400">معامل التحويل</label>
                            <input type="number" step="0.0001" name="conversion_factor" id="conversion_factor"
                                class="form-control w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" />
                        </div>
                    </div>

                    <x-button class="btn btn-success mt-2">حفظ </x-button>
                    {{-- <button type="submit" class="btn btn-success mt-2">حفظ</button> --}}
                </div>
            </div>
        </form>
        <!-- جدول عرض الوحدات -->
        <div class="overflow-x-auto mt-1">
            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">الوحدة الأب</th>
                        <th class="px-4 py-3">معامل التحويل</th>
                        <th class="px-4 py-3">الفرع</th>
                        <th class="px-4 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr
                            class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <td class="px-4 py-3">{{ $unit->name }}</td>
                            <td class="px-4 py-3">{{ $unit->parent?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $unit->conversion_factor ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $unit->branch_id ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <button class="btn btn-sm btn-primary"
                                    onclick="fillForm({{ $unit }})">تعديل</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- النموذج -->
       
    </div>

    <script>
        function fillForm(unit) {
            document.getElementById('unit-form').action = `/units/${unit.id}`;
            document.getElementById('form-method').value = 'POST';
            const hiddenMethod = document.createElement('input');
            hiddenMethod.type = 'hidden';
            hiddenMethod.name = '_method';
            hiddenMethod.value = 'POST';
            document.getElementById('unit-form').appendChild(hiddenMethod);

            document.getElementById('form-title').innerText = 'تعديل وحدة';
            document.getElementById('name').value = unit.name;
            document.getElementById('parent_unit_id').value = unit.parent_unit_id ?? '';
            document.getElementById('conversion_factor').value = unit.conversion_factor ?? '';
            document.getElementById('branch_id').value = unit.branch_id ?? '';
        }
    </script>

</x-layout>
