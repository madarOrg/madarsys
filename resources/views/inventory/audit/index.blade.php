<x-layout>

    <div class="container">
        <div class=" mb-4  flex justify-start mt-4">

        <x-title :title="'سجل عمليات الجرد'"></x-title>
            <a href="{{ route('inventory.audit.create') }}"
                class=" sm:w-auto h-12 shadow-sm rounded-lg text-base font-semibold leading-7 transition-all duration-300  text-gray-700 bg-green-600 hover:bg-green-700 px-6 py-2 flex items-center justify-center"> 
                
             إضافة جرد جديد</a>
        </div>
        <!-- فلترة البحث -->
        <form action="{{ route('inventory.audit.index') }}" method="GET" class="mb-4">
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
            <div class="hide-on-print  mb-4 mt-1">
                <button type="submit" class=" btn btn-primary text-indigo-600 hover:text-indigo-700">تصفية</button>
            </div>
        </form>

        <!-- عرض قائمة الجرد -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th>كود الجرد</th>
                        <th>نوع الجرد</th>
                        <th>تاريخ البدء</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>المستخدمين</th>
                        <th>المستودعات</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        <tr>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $audit->inventory_code }}
                            </td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                {{ $audit->inventory_type == 1 ? 'جرد دوري' : 'جرد مفاجئ' }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $audit->start_date }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $audit->end_date }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                {{ $audit->status == 1 ? 'معلق' : 'مكتمل' }}</td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                @foreach ($audit->users as $user)
                                    <span>{{ $user->name }}</span><br>
                                @endforeach
                            </td>
                            <td class="border p-2 w-auto min-w-[50px] whitespace-nowrap">
                                @foreach ($audit->warehouses as $warehouse)
                                    <span>{{ $warehouse->name }}</span><br>
                                @endforeach
                            </td>
                            <td class="border p-2   whitespace-nowrap">
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
