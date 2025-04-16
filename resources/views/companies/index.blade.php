<x-layout>

    <div class="relative mt-1 flex items-center">

        <x-title :title="'إدارة الشركات'"></x-title>

        <form method="GET" action="{{ route('companies.index') }}">
            <x-search-input id="custom-id" name="search" placeholder="ابحث عن الشركات" :value="request()->input('search')" />
        </form>
    </div>
    <x-button :href="route('companies.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة شركة جديدة
    </x-button>
    <!-- زر إضافة فرع جديد -->
    <x-button :href="route('branches.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة فرع جديد
    </x-button>
    <!-- زر إضافة مستودع جديد -->
    <x-button :href="route('warehouses.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة مستودع جديد
    </x-button>



    <!-- جدول الشركات -->
    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                {{-- <th class="p-4">
                    <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                </th> --}}
                <th class="px-6 py-3">اسم الشركة</th>
                <th class="px-6 py-3">الشعار</th>
                <th class="px-6 py-3">رقم الهاتف</th>
                <th class="px-6 py-3">البريد الإلكتروني</th>
                <th class="px-6 py-3">العنوان</th>
                <th class="px-6 py-3">معلومات إضافية</th>
                <th class="px-6 py-3">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr
                    class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    {{-- <td class="p-4">
                        <input type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                    </td> --}}
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                        <a href="javascript:void(0)"
                            onclick="toggleBranchesAndWarehouses({{ $company->id }})">{{ $company->name }}</a>
                    </td>
                    <td class="px-6 py-4">
                        @if ($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة"
                                class="w-10 h-10 rounded-full">
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $company->phone_number }}</td>
                    <td class="px-6 py-4">{{ $company->email }}</td>
                    <td class="px-6 py-4">{{ $company->address }}</td>
                    <td class="px-6 py-4">{{ $company->additional_info }}</td>
                    <td class="px-6 py-4 ">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">

                            <a href="{{ route('companies.edit', $company->id) }}"
                                class="text-blue-600 hover:underline dark:text-blue-500">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('companies.destroy', $company->id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- جدول الفروع -->
                <!-- جدول الفروع -->
                <tr id="branches-table-{{ $company->id }}" class="hidden">
                    <td colspan="8" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <x-title :title="'فروع الشركة: ' . $company->name" />

                        <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-600 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">اسم الفرع</th>
                                    <th class="px-6 py-3">العنوان</th>
                                    <th class="px-6 py-3">رقم الهاتف</th>
                                    <th class="px-6 py-3">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($company->branches ?? [] as $branch)
                                    <tr
                                        class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            <a href="javascript:void(0)"
                                                onclick="toggleBranchWarehouses({{ $branch->id }})">
                                                {{ $branch->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">{{ $branch->address }}</td>
                                        <td class="px-6 py-4">{{ $branch->contact_info }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3 rtl:space-x-reverse">

                                            <a href="{{ route('branches.edit', $branch->id) }}"
                                                class="text-blue-600 hover:underline dark:text-blue-500">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                        </td>
                                    </tr>

                                    <!-- جدول المستودعات الخاصة بالفرع -->
                                    <tr id="warehouses-table-{{ $branch->id }}" class="hidden">
                                        <td colspan="4" class="p-4 bg-gray-200 dark:bg-gray-800 rounded-md">
                                            <x-title :title="'مستودعات الفرع: ' . $branch->name" />

                                            <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                                                <thead
                                                    class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-600 dark:text-gray-400">
                                                    <tr>
                                                        <th class="px-6 py-3">اسم المستودع</th>
                                                        <th class="px-6 py-3">كود المستودع</th>
                                                        <th class="px-6 py-3">رقم الهاتف</th>
                                                        <th class="px-6 py-3">الإجراء</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($branch->warehouses ?? [] as $warehouse)
                                                        <tr
                                                            class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                            <td class="px-6 py-4">{{ $warehouse->name }}</td>
                                                            <td class="px-6 py-4">{{ $warehouse->code }}</td>
                                                            <td class="px-6 py-4">{{ $warehouse->contact_info }}</td>
                                                            <td class="px-6 py-4">
                                                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                                                <a href="{{ route('warehouses.edit', $warehouse->id) }}"
                                                                    class="text-blue-600 hover:underline dark:text-blue-500">
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </a>
                                                                <form
                                                                    action="{{ route('warehouses.destroy', $warehouse->id) }}"
                                                                    method="POST" class="inline-block">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="text-red-600 hover:text-red-800">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-gray-600">لا
                                                                توجد مستودعات</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-600">لا توجد فروع</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <script>
        function toggleBranchesAndWarehouses(companyId) {
            let branchesTable = document.getElementById(`branches-table-${companyId}`);
            branchesTable.classList.toggle("hidden");
        }

        function toggleBranchWarehouses(branchId) {
            let warehousesTable = document.getElementById(`warehouses-table-${branchId}`);
            warehousesTable.classList.toggle("hidden");
        }
    </script>
</x-layout>
