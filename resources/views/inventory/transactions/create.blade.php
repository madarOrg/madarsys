<x-layout>
    <section class="">
        <form action="{{ route('inventory.transactions.store') }}" method="POST">
            @csrf
            <!-- الحاوية الرئيسية للتوزيع -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <!-- بيانات الحركة (تأخذ ربع الصفحة) -->
                <div class="col-span-1  p-4 rounded-lg shadow w-full overflow-x-auto">
                    <x-title :title="' بيانات الحركة '"></x-title>

                    <!-- اختيار نوع العملية -->
                    <label for="transaction_type_id"
                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">نوع العملية</label>
                    <select name="transaction_type_id" id="transaction_type_id"
                        class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="">اختر نوع العملية</option>
                        @foreach ($transactionTypes as $transactionType)
                            <option value="{{ $transactionType->id }}" data-effect="{{ $transactionType->effect }}">
                                {{ $transactionType->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- تاريخ العملية -->
                    <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية" type="date"
                        required="true" />
                    <!-- حقل التأثير -->
                    <label for="effect" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2">
                        التأثير
                    </label>
                    <select name="effect" id="effect"
                        class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="1">+</option>
                        <option value="-1">-</option>
                    </select>
                    <input type="hidden" id="hidden-effect" name="effect" value="0">

                    <!-- الرقم المرجعي -->
                    <x-file-input id="reference" name="reference" label="الرقم المرجعي (اختياري)" type="text" />

                    <!-- الشريك -->
                    <label for="partner_id"
                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">الشريك</label>
                    <select id="partner_id" name="partner_id"
                        class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        @foreach ($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>

                    <!-- القسم -->
                    <label for="department_id"
                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">القسم</label>
                    <select id="department_id" name="department_id"
                        class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value=""></option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>

                    <!-- المستودع -->
                    <label for="warehouse_id"
                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                    <select id="warehouse_id" name="warehouse_id"
                        class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>

                    <!-- ملاحظات -->
                    <x-file-input id="notes" name="notes" label="ملاحظات" type="textarea" />

                    <!-- زر الإضافة -->
                    <div class="flex justify-end mt-4">
                        <x-button type="submit">
                        إضافة
                        </x-button>
                    </div>
                </div>

                <!-- تفاصيل العملية (تأخذ ثلاثة أرباع الصفحة) -->
                <div class="col-span-1 md:col-span-3  p-4 rounded-lg shadow w-full overflow-x-auto">
                    <x-title :title="' تفاصيل الحركة '"></x-title>
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
                        <tbody>
                            @foreach (old('products', []) as $index => $productId)
                                <tr
                                    class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                    <td class="px-6 py-4">
                                        <select name="products[]"
                                            class="w-full product-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر منتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="units[]"
                                            class="w-full units-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر وحدة</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="quantities[]"
                                            class="w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="unit_prices[]"
                                            class="w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                            min="0" step="0.01" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="totals[]"
                                            class="w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                            min="0" step="0.01" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="warehouse_locations[]"
                                            class="w-full warehouse-select bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر موقع التخزين</option>
                                            @foreach ($warehouseLocations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="button" class="text-red-600 hover:text-red-800"
                                            onclick="removeProductRow(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- زر إضافة منتج -->
                    <div class="flex justify-end mt-4">
                        <x-button-secondary type="button" onclick="addProductRow()">
                            + إضافة منتج
                        </x-button-secondary>
                    </div>
                    
                </div>
            </div>


        </form>
    </section>

    <!-- جافا سكريبت لإضافة وإزالة صفوف المنتجات -->
    <script>
        function addProductRow() {
            const tableBody = document.querySelector('table tbody');
            const newRow = `
                <tr class="product-row border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                    <td class="px-6 py-4">
                        <select name="products[]" 
                                class="w-full product-select   bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 mt-1 focus:outline-blue-500
                        ">
                            <option value="">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                             <select name="units[]" 
                                            class="w-full units-select
                                                bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 mt-1 focus:outline-blue-500
                                            ">
                            <option value="">اختر وحدة</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="quantities[]" class="w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="unit_prices[]" class="w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="totals[]" class="w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                    </td>
                    <td class="px-6 py-4">
                        <select name="warehouse_locations[]" 
                                            
                                            class="w-full warehouse-select  bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 mt-1 focus:outline-blue-500
                                            ">
                            <option value="">اختر موقع التخزين</option>
                            @foreach ($warehouseLocations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        }

        function removeProductRow(button) {
            button.closest('tr').remove();
        }
    </script>

    <!-- كود AJAX لجلب الوحدات بناءً على اختيار المنتج -->
    @vite([
        
        'resources/js/inventory.js',
        
    ])    

</x-layout>
