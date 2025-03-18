
{{-- @livewireStyles
@livewireScripts --}}

<div>
    <form id="transaction-form" wire:submit.prevent="submit">
        @csrf

        <!-- التقسيم الرئيسي: بيانات العملية وبيانات الأصناف -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

            <!-- قسم بيانات العملية (ربع الصفحة) -->
            <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'بيانات الحركة'" />

                <!-- نوع العملية -->
                {{-- <label for="transaction_type_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">نوع العملية</label>
                <select wire:model="transaction_type_id" id="transaction_type_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="">اختر نوع العملية</option>
                    @foreach ($transactionTypes as $transactionType)
                        <option value="{{ $transactionType->id }}" data-effect="{{ $transactionType->effect }}">{{ $transactionType->name }}</option>
                    @endforeach
                </select> --}}
                <select wire:model.defer="transaction_type_id" id="transaction_type_id" class="form-select w-full mt-1" wire:change="updateEffect">
                    <option value="">اختر نوع العملية</option>
                    @foreach ($transactionTypes as $transactionType)
                        <option value="{{ $transactionType->id }}" data-effect="{{ $transactionType->effect }}">
                            {{ $transactionType->name }}
                        </option>
                    @endforeach
                </select>
                
                <!-- تاريخ العملية -->
                <x-file-input wire:model="transaction_date" id="transaction_date" name="transaction_date" label="تاريخ العملية" type="datetime-local" required="true" value="{{ now()->format('Y-m-d\TH:i') }}" />

                <!-- التأثير (تحديث تلقائي عند اختيار نوع العملية) -->
                <label for="effect" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2">التأثير</label>
                <select wire:model="effect" id="effect" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="1">+</option>
                    <option value="-1">-</option>
                </select>

                <!-- الرقم المرجعي -->
                <x-file-input wire:model="reference" id="reference" name="reference" label="الرقم المرجعي (اختياري)" type="text" />

                <!-- الشريك -->
                <label for="partner_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">الشريك</label>
                <select wire:model="partner_id" id="partner_id" name="partner_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="" selected>اختر الشريك(مورد/عميل/مورد)</option>
                    @foreach ($partners as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                    @endforeach
                </select>

                <!-- القسم -->
                <label for="department_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">القسم</label>
                <select wire:model="department_id" id="department_id" name="department_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="">اختر القسم</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>

                <!-- المستودع -->
                <label for="warehouse_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">من المستودع</label>
                <select wire:model="warehouse_id" id="warehouse_id" name="warehouse_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="" selected>من المستودع</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>

                {{-- <!-- المستودع الثانوي (يظهر عند الحاجة) -->
                <div id="secondary_warehouse_container" style="display: none;">
                    <label for="secondary_warehouse_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع الثانوي</label>
                    <select wire:model="secondary_warehouse_id" id="secondary_warehouse_id" name="secondary_warehouse_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="">من مستودعًا</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
     <!-- المستودع الثانوي -->
     <div id="secondary_warehouse_container">
        <label for="secondary_warehouse_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400"> الى المستودع</label>
        <select wire:model="secondary_warehouse_id" id="secondary_warehouse_id" name="secondary_warehouse_id" class="form-select w-full mt-1">
            <option value="">الى المستودع</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
                <!-- الملاحظات -->
                <x-file-input wire:model="notes" id="notes" name="notes" label="ملاحظات" type="textarea" />

                <!-- زر الإدخال الرئيسي (يُستخدم للحفظ) -->
                <div class="flex justify-end mt-4">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </div>

            <!-- قسم تفاصيل الحركة (ثلاثة أرباع الصفحة) -->
            <div class="col-span-1 md:col-span-3 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'تفاصيل الحركة'" />

                <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                    <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">المنتج</th>
                            <th class="px-6 py-3">الوحدة</th>
                            <th class="px-6 py-3">الكمية</th>
                            <th class="px-6 py-3">سعر الوحدة</th>
                            <th class="px-6 py-3">الإجمالي</th>
                            {{-- <th class="px-6 py-3">موقع التخزين</th> --}}
                            <th class="px-6 py-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactionItems as $index => $item)
                            <tr class="product-row border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                <td>
                                    {{-- <select wire:model="transactionItems.{{ $index }}.product_id" class="form-select product-select"> --}}
                                        <select wire:model="transactionItems.{{ $index }}.product_id" wire:change="updateUnits({{ $index }})" class="form-select">
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{-- <select class="form-select units-select"> --}}

                                    <select wire:model="transactionItems.{{ $index }}.unit_id" class="form-select units-select">
                                        <option value="">اختر وحدة</option>
                                        @foreach($item['units'] as $unit)
                                            <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-2">
                                    <input wire:model="transactionItems.{{ $index }}.quantity" class="form-input w-full" type="number" step="1" wire:input="calculateTotal({{ $index }})" />
                                </td>
                                <td class="px-6 py-2">
                                    <input wire:model="transactionItems.{{ $index }}.unit_price" class="form-input w-full" type="number" step="0.01" wire:input="calculateTotal({{ $index }})" />
                                </td>
                                <td class="px-6 py-2">
                                    <input wire:model="transactionItems.{{ $index }}.total" class="form-input w-full" type="number" step="0.01" readonly />
                                </td>
                                {{-- <td class="px-6 py-2">
                                    <select wire:model="transactionItems.{{ $index }}.warehouse_location_id" class="form-select w-full mt-1">
                                        <option value="" disabled selected>اختر موقع التخزين</option>
                                        @foreach($warehouseLocations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </td> --}}
                                <td class="px-6 py-2">
                                    <button wire:click="removeProductRow({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end mt-4">
                    <button wire:click="addProductRow" type="button" class="bg-blue-500  px-4 py-2 rounded">
                        إضافة منتج
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
{{-- <script>
   document.addEventListener("DOMContentLoaded", function () {
    // تحديد العناصر المطلوبة
    const transactionTypeSelect = document.getElementById("transaction_type_id");
    const secondaryWarehouseContainer = document.getElementById("secondary_warehouse_container");

    // تعريف الدالة المسؤولة عن إظهار أو إخفاء المستودع الثانوي
    function toggleSecondaryWarehouse() {
        const selectedValue = transactionTypeSelect.value;
        
        // إظهار المستودع الثانوي فقط إذا كانت القيمة 5
        if (selectedValue === "5") {
            secondaryWarehouseContainer.style.display = "block";
        } else {
            secondaryWarehouseContainer.style.display = "none";
        }
    }

    // ربط الحدث بالتغيير واستدعاء الدالة عند التحميل
    transactionTypeSelect.addEventListener("change", function() {
        toggleSecondaryWarehouse();
    });

    // التأكد من ظهور المستودع الثانوي بناءً على القيمة الافتراضية
    toggleSecondaryWarehouse(); // للتحقق من القيمة الافتراضية عند تحميل الصفحة
});



</script> --}}

<script>
    document.addEventListener('livewire:load', function () {
        // استماع لحدث showSecondaryWarehouse من Livewire
        Livewire.on('showSecondaryWarehouse', function (eventData) {
            const secondaryWarehouseContainer = document.getElementById("secondary_warehouse_container");
            // إظهار أو إخفاء المستودع الثانوي بناءً على الحدث
            secondaryWarehouseContainer.style.display = eventData.show ? "block" : "none";
        });
    });
</script>