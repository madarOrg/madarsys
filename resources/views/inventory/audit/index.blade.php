<x-layout>

    <div class="container">
      
        <!-- فلترة البحث -->
        <form action="{{ route('inventory.audit.index') }}" method="GET" class="mb-4">
            <div x-data="{ open: true }">
                <!-- زر لفتح أو إغلاق القسم -->
                <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                    <span
                        x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                    </span>
                </button>
                 <!-- الحقول القابلة للطي -->
            <div x-show="open" x-transition>
            <div class="flex flex-wrap md:flex-nowrap gap-4 items-end w-full">
                <div class="flex-1 min-w-[200px]">
                    @include('components.file-input', [
                        'id' => 'start_date',
                        'label' => 'من تاريخ',
                        'name' => 'start_date',
                        'type' => 'date',
                        'attributes' => 'value="' . request()->input('start_date') . '"',
                    ])
                </div>

                <div class="flex-1 min-w-[200px]">
                    @include('components.file-input', [
                        'id' => 'end_date',
                        'label' => 'إلى تاريخ',
                        'name' => 'end_date',
                        'type' => 'date',
                        'attributes' => 'value="' . request()->input('end_date') . '"',
                    ])
                </div>
                        {{-- <div class="">
                        <label for="inventory_type">نوع الجرد</label>
                        <select name="inventory_type" id="inventory_type" class="form-control">
                            <option value="">الكل</option>
                            <option value="1" {{ request()->input('inventory_type') == 1 ? 'selected' : '' }}>جرد دوري</option>
                            <option value="2" {{ request()->input('inventory_type') == 2 ? 'selected' : '' }}>جرد مفاجئ</option>
                        </select>
                    </div>
                    <div class="">
                        <label for="status">الحالة</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">الكل</option>
                            <option value="1" {{ request()->input('status') == 1 ? 'selected' : '' }}>معلق</option>
                            <option value="0" {{ request()->input('status') == 0 ? 'selected' : '' }}>مكتمل</option>
                        </select>
                    </div> --}}
                    <div class="flex-1 min-w-[200px]">
                    @include('components.select-dropdown', [
                        'id' => 'inventory_type',
                        'name' => 'inventory_type',
                        'label' => 'نوع الجرد',
                        'options' => [
                            '1' => 'جرد دوري',
                            '2' => 'جرد مفاجئ',
                        ],
                        'selected' => request()->input('inventory_type'),
                    ])
                </div>

                <div class="flex-1 min-w-[200px]">
                    @include('components.select-dropdown', [
                        'id' => 'status',
                        'name' => 'status',
                        'label' => 'الحالة',
                        'options' => [
                            '1' => 'معلق',
                            '0' => 'مكتمل',
                        ],
                        'selected' => request()->input('status'),
                    ])
                </div>

            </div>
            {{-- <div class="hide-on-print  mb-4 mt-1">
                <button type="submit" class=" btn btn-primary text-indigo-600 hover:text-indigo-700">تصفية</button>
            </div> --}}
            <div class="flex justify-end mt-2">
                <x-button>
                    بحث
                </x-button>
            </div>
        </div>
        </form>
        {{-- <a href="{{ route('inventory.audit.create') }}"
        class=" sm:w-auto h-12 shadow-sm rounded-lg text-base font-semibold leading-7 transition-all duration-300  text-gray-700 bg-green-600 hover:bg-green-700 px-6 py-2 flex items-center justify-center"> 
        
     إضافة جرد جديد</a> --}}
     <div class=" flex justify-start ">
        <x-title :title="'سجل عمليات الجرد'"></x-title>
     <x-button :href="route('inventory.audit.create')" type="button" class="ml-4">
        <i class="fas fa-plus mr-2"></i> إضافة جرد جديد
    </x-button>
</div>
        <!-- عرض قائمة الجرد -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="px-6 py-3 text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                   
                    <tr class="">
                        <th class="px-6 py-3">كود الجرد</th>
                        <th class="px-6 py-3">نوع الجرد</th>
                        <th class="px-6 py-3">تاريخ البدء</th>
                        <th class="px-6 py-3">تاريخ الانتهاء</th>
                        <th class="px-6 py-3">الحالة</th>
                        <th class="px-6 py-3">المستخدمين</th>
                        <th class="px-6 py-3">المستودعات</th>
                        <th class="px-6 py-3">العمليات</th>
                    </tr>
                </thead>
                <tbody class=" p-2 w-auto min-w-[50px] whitespace-nowrap">
                    @foreach ($audits as $audit)
                        <tr  class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $audit->inventory_code }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $audit->inventory_type == 1 ? 'جرد دوري' : 'جرد مفاجئ' }}</td>
                            <td class="px-6 py-4">{{ $audit->start_date }}</td>
                            <td class="px-6 py-4">{{ $audit->end_date }}</td>
                            <td class="px-6 py-4">
                                {{ $audit->status == 1 ? 'معلق' : 'مكتمل' }}</td>
                            <td class="px-6 py-4">
                                @foreach ($audit->users as $user)
                                    <span>{{ $user->name }}</span><br>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($audit->warehouses as $warehouse)
                                    <span>{{ $warehouse->name }}</span><br>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('inventory.audit.edit', $audit->id) }}"
                                    class="text-blue-600 hover:underline dark:text-blue-500 mx-2">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            
                                <form action="{{ route('inventory.audit.destroy', $audit->id) }}" method="POST"
                                    style="display:inline;" class="mx-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            
                                <a href="{{ route('inventory.audit.warehouseReport', [
                                    'inventory_code' => $audit->inventory_code,
                                    'start_date' => \Carbon\Carbon::parse($audit->start_date)->format('Y-m-d'),
                                    'end_date' => \Carbon\Carbon::parse($audit->end_date)->format('Y-m-d'),
                                ]) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                            
                                <a href="{{ route('inventory.transactions.create') . '?transaction_type_id[]=8' }}"
                                    class="btn btn-info mt-3 mx-2 text-green-600">
                                    <i class="fas fa-plus mr-2"></i>
                                </a>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
           

        </div>

</x-layout>
