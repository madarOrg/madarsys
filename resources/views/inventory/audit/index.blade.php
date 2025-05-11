<x-layout>

    {{-- <div class="container"> --}}
    <div class="max-w-7xl mx-auto px-4">

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
                       
                        <div class="flex-1 min-w-[200px]">
                            @if ($subTypes->isNotEmpty())
                                <div class="mb-2">
                                    <label for="subType"
                                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">الأنواع
                                        الفرعية</label>
                                    <select name="subType" id="subType"
                                        class="form-select w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                                 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                                     duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                        @foreach ($subTypes as $subType)
                                            <option value="{{ $subType->id }}">{{ $subType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-[200px] ">
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
            <table class="audit-items-table w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">كود الجرد</th>
                        <th class="px-6 py-3">نوع الجرد</th>
                        <th class="px-6 py-3">تاريخ البدء</th>
                        <th class="px-6 py-3">تاريخ الانتهاء</th>
                        <th class="px-6 py-3">الحالة</th>
                        <th class="px-6 py-3">المستخدمين</th>
                        <th class="px-6 py-3">المستودعات</th>
                        <th class="px-6 py-3">الاحراءات</th>
                    </tr>
                </thead>
                <tbody class=" p-2 w-auto min-w-[50px] whitespace-nowrap">
                    @foreach ($audits as $audit)
                        <tr
                            class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $audit->inventory_code }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $audit->subType?->name ?? 'غير محدد' }}

                            </td>
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
                                {{-- <a href="{{ route('inventory.audit.edit', $audit->id) }}"
                                    class="text-blue-600 hover:underline dark:text-blue-500 mx-2">
                                    <i class="fa-solid fa-pen"></i>
                                </a> --}}

                                <form action="{{ route('inventory.audit.destroy', $audit->id) }}" method="POST"
                                    style="display:inline;" class="delete-form mx-2">
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
                                    <i class="fas fa-eye "></i>
                                </a>

                                {{-- بدل id="auditButton" و onclick --}}
                                <a href="#" class="start-audit-button btn btn-info mt-3 mx-2 text-green-600"
                                    data-audit-id="{{ $audit->id }}"
                                    data-warehouse-ids='@json($audit->warehouses->pluck('id'))'>
                                    <i class="fas fa-plus mr-2"></i> تنفيذ الجرد
                                </a>



                                <a href="{{ route('inventory.audit.editTrans', ['id' => $audit->id]) }}"
                                    class="btn btn-primary">
                                    <i class="fa-solid fa-pen mr-2"></i>
                                </a>
                                @if ($audit)
                                    <a href="{{ route('inventory.audit.report', ['id' => $audit->id]) }}"
                                        class="font-medium text-purple-600 dark:text-purple-400 hover:underline mx-2"
                                        target="_blank">
                                        <i class="fas fa-file-alt mr-2"></i>
                                    </a>
                                @endif


                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.start-audit-button').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();

                const auditId = btn.dataset.auditId;
                const warehouseIds = JSON.parse(btn.dataset.warehouseIds);

                if (!confirm("هل أنت متأكد أنك تريد تنفيذ الجرد لجميع المستودعات؟")) {
                    return;
                }

                // نفّذ العمليات واحد واحد
                for (const wid of warehouseIds) {
                    try {
                        const response = await fetch(
                            `/inventory/audit/audit-transaction/${auditId}/${wid}`, {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                }
                            }
                        );

                        if (!response.ok) {
                            throw new Error(`فشل للمستودع رقم ${wid}`);
                        }

                        const data = await response.json();

                        // لو رجع لنا redirect_url ننتقل فوراً
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                            return; // نوقف الحلقة بعد التحويل
                        }
                    } catch (err) {
                        alert(err.message);
                        return; // توقف لو صار خطأ
                    }
                }

                // لو خلصنا الحلقة بدون redirect_url
                alert("لم يتم إنشاء أي عملية جرد بنجاح.");
            });
        });
    });
</script>
