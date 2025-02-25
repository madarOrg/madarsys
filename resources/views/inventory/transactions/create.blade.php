<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form id="transaction-form" action="{{ route('inventory.transactions.store') }}" method="POST">
            @csrf

            <!-- التقسيم الرئيسي: بيانات العملية وبيانات الأصناف -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <!-- قسم بيانات العملية (ربع الصفحة) -->
                <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                    <x-title :title="'بيانات الحركة'" />

                    <!-- نوع العملية -->
                    <label for="transaction_type_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">نوع العملية</label>
                    <select name="transaction_type_id" id="transaction_type_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="">اختر نوع العملية</option>
                        @foreach ($transactionTypes as $transactionType)
                            <option value="{{ $transactionType->id }}" data-effect="{{ $transactionType->effect }}">{{ $transactionType->name }}</option>
                        @endforeach
                    </select>

                    <!-- تاريخ العملية -->
                    <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية" type="date" required="true" />

                    <!-- التأثير (تحديث تلقائي عند اختيار نوع العملية) -->
                    <label for="effect" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2">التأثير</label>
                    <select id="effect" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="1">+</option>
                        <option value="-1">-</option>
                    </select>
                    <input type="hidden" id="hidden-effect" name="effect" value="0">

                    <!-- الرقم المرجعي -->
                    <x-file-input id="reference" name="reference" label="الرقم المرجعي (اختياري)" type="text" />

                    <!-- الشريك -->
                    <label for="partner_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">الشريك</label>
                    <select id="partner_id" name="partner_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        @foreach ($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>

                    <!-- القسم -->
                    <label for="department_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">القسم</label>
                    <select id="department_id" name="department_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value=""></option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>

                    <!-- المستودع -->
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                    <select id="warehouse_id" name="warehouse_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="" disabled selected>اختر مستودعًا</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>

                    <!-- المستودع الثانوي (يظهر عند الحاجة) -->
                    <div id="secondary_warehouse_container" style="display: none;">
                        <label for="secondary_warehouse_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع الثانوي</label>
                        <select id="secondary_warehouse_id" name="secondary_warehouse_id" class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                            <option value="">اختر مستودعًا</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الملاحظات -->
                    <x-file-input id="notes" name="notes" label="ملاحظات" type="textarea" />

                    <!-- زر الإدخال الرئيسي (يُستخدم للحفظ ) -->
                    <div class="flex justify-end mt-4">
                        <x-button type="submit" onclick="saveTemporary()">حفظ </x-button>
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
                                <th class="px-6 py-3">موقع التخزين</th>
                                <th class="px-6 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="transaction-items">
                            @foreach(old('products', []) as $index => $productId)
                                <tr class="product-row border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                    <td class="px-6 py-4">
                                        <select name="products[]" class="w-full product-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر منتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="units[]" class="w-full units-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر وحدة</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="quantities[]" class="w-full quantity-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="unit_prices[]" class="w-full unit-price-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="totals[]" class="w-full total-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="warehouse_locations[]" class="w-full warehouse-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر موقع التخزين</option>
                                            @foreach ($warehouseLocations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <!-- زر تحديث الصف: عند النقر يتم استدعاء دالة JavaScript لتحديث بيانات الصف عبر AJAX -->
                                        <button type="button" class="update-row-btn text-blue-600 hover:text-blue-800" onclick="updateRow(this)">تحديث</button>
                                        <!-- زر حذف الصف -->
                                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- زر إضافة صف جديد -->
                    <div class="flex justify-start mt-4">
                        <x-button-secondary type="button" onclick="addProductRow()">+ إضافة منتج</x-button-secondary>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <!-- JavaScript للتعامل مع تحديث الصفوف والحفظ المؤقت -->
    <script>
        // دالة لإضافة صف منتج جديد
        function addProductRow() {
            const tableBody = document.getElementById('transaction-items');
            const newRow = `
                <tr class="product-row border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                    <td class="px-6 py-4">
                        <select name="products[]" class="w-full product-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                            <option value="">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <select name="units[]" class="w-full units-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                            <option value="">اختر وحدة</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="quantities[]" class="w-full quantity-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="unit_prices[]" class="w-full unit-price-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="totals[]" class="w-full total-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                    </td>
                    <td class="px-6 py-4">
                        <select name="warehouse_locations[]" class="w-full warehouse-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                            <option value="">اختر موقع التخزين</option>
                            @foreach ($warehouseLocations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4 flex space-x-2">
                        <button type="button" class="update-row-btn text-blue-600 hover:text-blue-800" onclick="updateRow(this)">تحديث</button>
                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        }

        // دالة لحذف صف منتج
        function removeProductRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        // دالة لتحديث بيانات صف منتج معين باستخدام AJAX
        function updateRow(button) {
            const row = button.closest('tr');
            const product = row.querySelector('[name="products[]"]').value;
            const quantity = row.querySelector('[name="quantities[]"]').value;
            const unitPrice = row.querySelector('[name="unit_prices[]"]').value;
            const total = row.querySelector('[name="totals[]"]').value;
            
            // مثال على استدعاء AJAX (يمكنك تعديل الـ endpoint والبيانات حسب احتياجاتك)
            fetch('/inventory/transactions/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product: product,
                    quantity: quantity,
                    unitPrice: unitPrice,
                    total: total
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    alert('تم تحديث الصف بنجاح');
                } else {
                    alert('حدث خطأ أثناء التحديث');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // دالة لحفظ البيانات الكاملة للحركة مؤقتًا
        function saveTemporary() {
            console.log('saveTemporary() تم استدعاؤها');
            
            const form = document.getElementById('transaction-form');
            const formData = new FormData(form);
            // event.preventDefault(); // منع إعادة تحميل الصفحة

            fetch(form.action, {
                method: 'POST',
                body: formData,
                // headers: {
                //     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                // }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('تم الحفظ المؤقت للحركة');
                } else {
                    alert('حدث خطأ أثناء الحفظ');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // التعامل مع عرض/إخفاء المستودع الثانوي بناءً على نوع العملية
        document.addEventListener("DOMContentLoaded", function () {
            const transactionTypeSelect = document.getElementById("transaction_type_id");
            const secondaryWarehouseContainer = document.getElementById("secondary_warehouse_container");

            function toggleSecondaryWarehouse() {
                const selectedTransaction = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
                // مثال: إذا كان اسم العملية يحتوي على "تحويل مخزني"، نعرض المستودع الثانوي
                const isStockTransfer = selectedTransaction.text.includes("تحويل مخزني");
                secondaryWarehouseContainer.style.display = isStockTransfer ? "block" : "none";
            }

            transactionTypeSelect.addEventListener("change", toggleSecondaryWarehouse);
            toggleSecondaryWarehouse();
        });
    </script>

    @vite(['resources/js/inventory.js'])
</x-layout>
