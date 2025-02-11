<x-layout>
    <section class="">
        <form action="{{ route('inventory.transactions.store') }}" method="POST">
            @csrf

            <div class="space-y-12  mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة حركة مخزنية جديدة'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 ">
                        يرجى إدخال بيانات الحركة المخزنية الجديدة وتفاصيلها بدقة
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                        <!-- بيانات الحركة -->
                        <!-- قائمة اختيار نوع العملية -->
                        <div class="mb-3 ">
                            <label for="transaction_type_id"
                                class="form-label block text-sm font-medium text-gray-600 dark:text-gray-400">نوع
                                العملية</label>
                            <select name="transaction_type_id" id="transaction_type_id"
                                class="form-select w-full  bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">
                                <option value="">اختر نوع العملية</option>
                                @foreach ($transactionTypes as $transactionType)
                                    <option value="{{ $transactionType->id }}"
                                        data-effect="{{ $transactionType->effect }}">
                                        {{ $transactionType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- حقل التأثير الذي اسمه effect -->
                        <div class="m-3">
                            <label for="effect"
                                class="form-label block text-sm font-medium text-gray-600 dark:text-gray-400">
                                التأثير <span class="text-red-500 text-xs mt-1">(غير متاح للتعديل)</span>
                            </label>

                            <!-- حقل التأثير المرئي، لكنه قابل للقراءة فقط -->
                            <select id="effect"
                                class="form-select w-full bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                readonly>
                                <option value="+">+</option>
                                <option value="-">-</option>
                            </select>

                            <!-- حقل مخفي لضمان إرسال القيمة -->
                            <input type="hidden" name="effect" id="effect-hidden">
                        </div>


                        <div class="col-span-1">
                            <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية"
                                type="date" required="true" />
                            @error('transaction_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="reference" name="reference" label=" الرقم المرجعي (اختياري) "
                                type="text" />
                            @error('reference')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <label for="partner_id"
                                class=" form-label block text-sm font-medium text-gray-600 dark:text-gray-400">الشريك</label>
                            <select id="partner_id" name="partner_id"
                                class="form-select w-full  bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">
                                @foreach ($partners as $partner)
                                    <option value="{{ $partner->id }}"
                                        {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                        {{ $partner->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('partner_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <label for="department_id"
                                class="form-label block text-sm font-medium text-gray-600 dark:text-gray-400 ">القسم</label>
                            <select id="department_id" name="department_id"
                                class="form-select w-full  bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">
                                <option value="" {{ old('department_id') == null ? 'selected' : '' }}></option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                        <div class="col-span-1">
                            <label for="warehouse_id"
                                class="form-label block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                            <select id="warehouse_id" name="warehouse_id"
                                class="form-select w-full  bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1"
                                required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="notes" name="notes" label="ملاحظات" type="textarea" />
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- جدول التفاصيل -->
                    <div class="mt-6">
                        <x-title :title="'تفاصيل المنتجات'"></x-title>

                        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700  bg-gray-400 dark:bg-gray-700 dark:text-gray-400 ">
                                <tr>
                                    <th class="px-6 py-3">المنتج</th>
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
                                            <select name="products[]" id="products-{{ $index }}" class="w-full">
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ old('products')[$index] == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-6 py-4">
                                            <input type="number" name="quantities[]"
                                                class=" w-full bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1  "
                                                value="{{ old('quantities')[$index] ?? '' }}" />
                                        </td>

                                        <td class="px-6 py-4">
                                            <input type="number" name="unit_prices[]"
                                                class="w-full  bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                                value="{{ old('unit_prices')[$index] ?? '' }}" min="0"
                                                step="0.01" />
                                        </td>
                                        <input type="hidden" id="units-input" name="units[]"> <!-- هذا الحقل سيتم تحديثه تلقائيًا -->

                                        <td class="px-6 py-4">
                                            <input type="number" name="totals[]"
                                                class="w-full  bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                                value="{{ old('totals')[$index] ?? '' }}" min="0"
                                                step="0.01" />
                                        </td>

                                        <td class="px-6 py-4">
                                            <select name="warehouse_locations[]"
                                                id="warehouse_locations-{{ $index }}"
                                                class="w-full  bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1">
                                                @foreach ($warehouseLocations as $location)
                                                    <option value="{{ $location->id }}"
                                                        {{ old('warehouse_locations')[$index] == $location->id ? 'selected' : '' }}>
                                                        {{ $location->name }}
                                                    </option>
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

                        <button type="button" class="mt-4 text-blue-600 hover:text-blue-800"
                            onclick="addProductRow()">
                            <i class="fas fa-plus mr-2"></i> إضافة منتج جديد
                        </button>
                    </div>

                    <div class="sm:col-span-6 flex justify-end mt-6">
                        <x-button type="submit">حفظ الحركة المخزنية</x-button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <script>
        function addProductRow() {
            // إيجاد جسم الجدول (tbody)
            const tableBody = document.querySelector('table tbody');

            // إنشاء صف جديد باستخدام قالب HTML ثابت
            const newRow = `
                <tr class="product-row">
                    <td class="px-6 py-4">
                        <select name="products[]" class="w-full product-select">
                            <option value="">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <select name="units[]" class="w-full units-select">
                            <option value="">اختر وحدة</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="quantities[]" class="w-full" />
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="unit_prices[]" class="w-full" min="0" step="0.01" />
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="totals[]" class="w-full" min="0" step="0.01" />
                    </td>
                    <td class="px-6 py-4">
                        <select name="warehouse_locations[]" class="w-full">
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

            // إضافة الصف الجديد في نهاية جسم الجدول
            tableBody.insertAdjacentHTML('beforeend', newRow);
        }

        function removeProductRow(button) {
            button.closest('tr').remove();
        }
    </script>

    <!-- جافا سكربت للتعامل مع اختيار المنتج وعرض الوحدات المرتبطة -->
    <script>
        document.addEventListener('change', function(event) {
            // تحقق مما إذا كان العنصر الذي حدث عليه التغيير هو select للمنتجات
            if (event.target.classList.contains('product-select')) {
                let productId = event.target.value;

                // الحصول على الصف الذي يحتوي على هذا العنصر
                let row = event.target.closest('.product-row');
                if (!row) return; // التأكد من العثور على الصف

                // العثور على قائمة الوحدات في نفس الصف
                let unitsSelect = row.querySelector('.units-select');
                if (!unitsSelect) return; // في حال عدم وجودها

                // مسح الخيارات السابقة في قائمة الوحدات
                unitsSelect.innerHTML = '<option value="">اختر وحدة</option>';
                console.log("تم مسح الخيارات السابقة للوحدات في الصف.");

                // إذا تم اختيار منتج، يتم جلب الوحدات المرتبطة به
                if (productId) {
                    console.log("تم اختيار المنتج برقم:", productId);
                    fetch(`/get-units/${productId}`)
                        .then(response => {
                            console.log("تم استلام الاستجابة من الخادم:", response);
                            return response.json();
                        })
                        .then(data => {
                            console.log("البيانات المستلمة:", data);
                            data.units.forEach(unit => {
                                let option = document.createElement('option');
                                option.value = unit.id;
                                option.textContent = unit.name;
                                console.log("إضافة خيار:", option);
                                unitsSelect.appendChild(option);
                            });
                            console.log("تم تحديث قائمة الوحدات في الصف.");
                        })
                        .catch(error => {
                            console.error("خطأ في جلب الوحدات:", error);
                        });
                }
            }
        });
    </script>

    <script>
        function updateQuantitiesBasedOnEffect() {
            const effect = document.getElementById('effect').value;
            const quantityInputs = document.querySelectorAll('input[name="quantities[]"]');

            quantityInputs.forEach(function(input) {
                let value = parseFloat(input.value);

                if (!isNaN(value)) {
                    if (effect === '-') {
                        input.value = -Math.abs(value); // تحويل القيمة إلى سالب
                    } else if (effect === '+') {
                        input.value = Math.abs(value); // تحويل القيمة إلى موجب
                    }
                }

                // تأكد من تحديث الحقل فوراً بحيث يتم حفظ القيم المعدلة في حال تم حفظ النموذج لاحقاً
                input.dispatchEvent(new Event('input')); // محاكاة حدث "input" لضمان التحديث
            });
        }

        function updateQuantitiesBasedOnEffect() {
            const effect = document.getElementById('effect').value;
            const quantityInputs = document.querySelectorAll('input[name="quantities[]"]');

            quantityInputs.forEach(function(input) {
                let value = parseFloat(input.value);

                if (!isNaN(value)) {
                    if (effect === '-') {
                        input.value = -Math.abs(value); // تحويل القيمة إلى سالب
                    } else if (effect === '+') {
                        input.value = Math.abs(value); // تحويل القيمة إلى موجب
                    }
                }

                // تأكد من تحديث الحقل فوراً بحيث يتم حفظ القيم المعدلة في حال تم حفظ النموذج لاحقاً
                input.dispatchEvent(new Event('input')); // محاكاة حدث "input" لضمان التحديث
            });

            // تحديث الحقل المخفي لتضمين التأثير عند تغيير التأثير
            const effectHidden = document.getElementById('effect-hidden');
            effectHidden.value = effect;
        }

        // الحدث عند تغيير قيمة "transaction_type_id"
        document.getElementById('transaction_type_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const effect = selectedOption.getAttribute('data-effect');
            const effectSelect = document.getElementById('effect');

            // تحديد التأثير بناءً على الخيار
            if (effect === "0") {
                effectSelect.value = "+"; // افتراضي
            } else {
                effectSelect.value = effect;
            }

            // تحديث القيم بناءً على التأثير
            updateQuantitiesBasedOnEffect();
        });

        // تحديث القيم بناءً على التأثير عند تغيير التأثير مباشرة
        document.getElementById('effect').addEventListener('change', function() {
            updateQuantitiesBasedOnEffect();
        });
    </script>
</x-layout>
