<x-layout>
    <section class="">
        <form id="transaction-form" action="{{ route('inventory.transactions.store') }}" method="POST">
            @csrf

            <!-- التقسيم الرئيسي: بيانات العملية وبيانات الأصناف -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <!-- قسم بيانات العملية (ربع الصفحة) -->
                <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                    <x-title :title="'بيانات الحركة'" />

                    <!-- نوع العملية -->
                    <label for="transaction_type_id" class="">نوع العملية</label>
                    <select name="transaction_type_id" id="transaction_type_id" class="form-select tom-select  ">
                        <option value="">اختر نوع العملية</option>
                        @foreach ($transactionTypes as $transactionType)
                            <option value="{{ $transactionType->id }}" data-effect="{{ $transactionType->effect }}">
                                {{ $transactionType->name }}</option>
                        @endforeach
                    </select>

                    <!-- تاريخ العملية -->
                    <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية"
                        type="datetime-local" required="true" value="{{ now()->format('Y-m-d\TH:i') }}" />

                    <!-- التأثير (تحديث تلقائي عند اختيار نوع العملية) -->
                    <label for="effect" class=" mt-2">التأثير</label>
                    <select id="effect"
                        class="form-select w-full mt-1 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                        <option value="1">+</option>
                        <option value="-1">-</option>
                    </select>
                    <input type="hidden" id="hidden-effect" name="effect" value="{{ old('effect', $effect ?? '0') }}">

                    <!-- الرقم المرجعي -->
                    <x-file-input id="reference" name="reference" label="الرقم المرجعي (اختياري)" type="text" />

                    <!-- الشريك -->
                    <label for="partner_id" class="">الشريك</label>
                    <select id="partner_id" name="partner_id" class="form-select tom-select ">
                        @foreach ($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>

                    {{-- <!-- القسم -->
                    <label for="department_id" class="">القسم</label>
                    <select id="department_id" name="department_id" class="form-select">
                        <option value=""></option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select> --}}

                    <!-- المستودع -->
                    <label for="warehouse_id" class="">المستودع</label>
                    <select id="warehouse_id" name="warehouse_id" class="form-select tom-select ">
                        <option value="" disabled selected>اختر مستودعًا</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>

                    <!-- المستودع الثانوي (يظهر عند الحاجة) -->
                    <div id="secondary_warehouse_container" style="display: none;">
                        <label for="secondary_warehouse_id" class="">المستودع
                            الثانوي</label>
                        <select id="secondary_warehouse_id" name="secondary_warehouse_id" class="form-select ">
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
                        <x-button type="submit">حفظ </x-button>
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
                                <th class="px-6 py-3">موقغ التخزين</th>
                                <th class="px-6 py-3">تاريخ إنتاج المنتج </th>
                                <th class="px-6 py-3">تاريخ إنتهاء المنتج </th>

                                <th class="px-6 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="transaction-items">
                            @foreach (old('products', []) as $index => $productId)
                                <tr
                                    class="product-row border-b  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                    <td class="px-0 py-4">
                                        <select name="products[]"
                                            class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر منتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                   
                                    
                                    <td class="px-6 py-4">
                                        <select name="units[]"
                                            class="w-full units-select tom-select  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر وحدة</option>
                                        </select>
                                    </td>
                                    <td class="">
                                        <input type="number" name="quantities[]"
                                            class="w-full quantity-input  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                                    <td class="">
                                        <input type="number" name="unit_prices[]"
                                            class="w-full unit-price-input border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                            min="0" step="0.01" />
                                    </td>
                                    <td class="">
                                        <input type="number" name="totals[]"
                                            class="w-full total-input  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1"
                                            min="0" step="0.01" />
                                    </td>
                                    <td class="">
                                        <input type="date" name="production_date[]"
                                            class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>
                                    <td class="">
                                        <input type="date" name="expiration_date[]"
                                            class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                                    </td>

                                    <td class="">
                                        <select name="warehouse_locations[]"
                                            class=" warehouse-select  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                                            <option value="">اختر موقع التخزين</option>
                                            @foreach ($warehouseLocations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <!-- زر تحديث الصف: عند النقر يتم استدعاء دالة JavaScript لتحديث بيانات الصف عبر AJAX -->
                                        {{-- <button type="button"
                                            class="update-row-btn text-blue-600 hover:text-blue-800"
                                            onclick="updateRow(this)">تحديث</button> --}}
                                        <!-- زر حذف الصف -->
                                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800"
                                            onclick="removeProductRow(this)">
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
                <tr id="productRows" class="product-row border-b  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                    <td class="px-0 py-2">
                        <select name="products[]" 
                                            class="w-full product-select tom-select min-w-[250px] border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                            <option value="">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
            {{ $product->name }} - SKU: {{ $product->sku }} - Barcode: {{ $product->barcode }}
                                     </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <select name="units[]" class="w-full units-select tom-select  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                            <option value="">اختر وحدة</option>
                        </select>
                    </td>
                    <td class="">
                        <input type="number" name="quantities[]" class="w-full quantity-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
                    </td>
                    <td class="">
                        <input type="number" name="unit_prices[]" class="w-full unit-price-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                    </td>
                    <td class="">
                        <input type="number" name="totals[]" class="w-full total-input bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" min="0" step="0.01" />
                    </td>
                   <td class="">
                <select name="warehouse_locations[]" 
                    class="warehouse-select tom-select  border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500">
                    <option value="">اختر موقع التخزين</option>
                    @foreach ($warehouseLocations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </td>
                     <td class="">
            <input type="date" name="production_date[]" class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
            </td>
            <td class="">
                <input type="date" name="expiration_date[]" class="w-full border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-3 py-1" />
            </td>
            <td class="px-6 py-4">
                <button type="button" class="remove-row-btn text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);


            const newSelect = tableBody.querySelector('.product-row:last-child .product-select');
            if (newSelect) {
                new TomSelect(newSelect, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "اختر منتج",
                });
            }
            localStorage.removeItem('currentTransactionId');
            generateTransactionId();
            document.querySelector('#productRows').innerHTML = ''; // مسح الصفوف السابقة

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
                    if (data.success) {
                        alert('تم تحديث الصف بنجاح');
                    } else {
                        alert('حدث خطأ أثناء التحديث');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // // دالة لحفظ البيانات الكاملة للحركة مؤقتًا
        // function saveTemporary() {
        //     console.log('saveTemporary() تم استدعاؤها');

        //     const form = document.getElementById('transaction-form');
        //     const formData = new FormData(form);
        //     // event.preventDefault(); // منع إعادة تحميل الصفحة

        //     fetch(form.action, {
        //         method: 'POST',
        //         body: formData,
        //         // headers: {
        //         //     'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         // }
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if(data.success) {
        //             alert('تم الحفظ المؤقت للحركة');
        //         } else {
        //             alert('حدث خطأ أثناء الحفظ');
        //         }
        //     })
        //     .catch(error => console.error('Error:', error));
        // }

        // التعامل مع عرض/إخفاء المستودع الثانوي بناءً على نوع العملية
        document.addEventListener("DOMContentLoaded", function() {
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

        document.addEventListener('change', function(event) {
            if (event.target.classList.contains('product-select')) {
                const productId = event.target.value;
                const row = event.target.closest('.product-row');
                if (!row) return;
                populateUnits(row, productId);
            }
        });

        // دالة لتعبئة قائمة الوحدات بناءً على معرف المنتج
        function populateUnits(row, productId) {
            console.log("Livewire is ready, JS is running...");

            const unitsSelect = row.querySelector('.units-select');
            if (!unitsSelect) return;

            // إعادة تعيين قائمة الوحدات مع خيار افتراضي
            unitsSelect.innerHTML = '<option value="">اختر وحدة</option>';

            if (productId) {
                fetch(`/get-units/${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        const defaultUnitId = data.default_unit_id; // وحدة المنتج الافتراضية
                        data.units.forEach(unit => {
                            const option = document.createElement('option');
                            option.value = unit.id;
                            option.textContent = unit.name;
                            // إذا كانت الوحدة هي الافتراضية، نجعلها المختارة
                            if (unit.id == defaultUnitId) {
                                option.selected = true;
                            }
                            unitsSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error("خطأ في جلب الوحدات:", error);
                    });
            }
        }

        // document.addEventListener('DOMContentLoaded', function() {
        //     // تأكد من أن TomSelect تم تحميله بشكل صحيح
        //     if (typeof TomSelect === 'undefined') {
        //         console.error("TomSelect is not loaded.");
        //         return;
        //     }

        //     // تهيئة الـ TomSelect لجميع عناصر الـ select
        //     document.querySelectorAll('.product-row').forEach(row => {
        //         const productSelect = row.querySelector('.product-select');
        //         if (productSelect) {
        //             // تعبئة الوحدات عند تحميل الصفحة إذا كان هناك قيمة
        //             if (productSelect.value) {
        //                 populateUnits(row, productSelect.value);
        //             }
        //             // تهيئة TomSelect
        //             new TomSelect(productSelect, {
        //                 create: false,
        //                 sortField: {
        //                     field: "text",
        //                     direction: "asc"
        //                 },
        //                 placeholder: "اختر منتج",
        //             });
        //         }
        //     });
        // });

        // الاستماع لتغيير اختيار المنتج وتعبئة الوحدات بناءً عليه
        document.addEventListener('change', function(event) {
            if (event.target.classList.contains('product-select')) {
                const productId = event.target.value;
                const row = event.target.closest('.product-row');
                if (!row) return;
                populateUnits(row, productId);
            }
        });


        /////effect/////////////////////////////////////////////
        // تحديث قيمة effect عند تغيير نوع العملية
        function updateEffectValue() {
            const transactionTypeSelect = document.getElementById('transaction_type_id');
            const effectSelect = document.getElementById('effect');
            const hiddenEffectInput = document.getElementById('hidden-effect');

            // استرجاع القيمة المحفوظة عند العودة للخلف
            const savedEffect = hiddenEffectInput.value;

            // استرجاع القيمة الجديدة بناءً على الاختيار
            const selectedOption = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
            const effectValue = selectedOption.getAttribute('data-effect');

            if (effectValue !== "0") {
                // إذا لم تكن القيمة 0، يتم التحديث تلقائيًا
                effectSelect.value = effectValue;
                hiddenEffectInput.value = effectValue;
                effectSelect.disabled = true;
            } else {
                // إذا كانت القيمة 0، يتم تمكين التعديل اليدوي
                effectSelect.disabled = false;

                // الاحتفاظ بالقيمة المحفوظة عند العودة للخلف
                if (savedEffect) {
                    effectSelect.value = savedEffect;
                }
            }
        }

        // تحديث القيمة المخفية عند تغيير effect يدويًا
        document.getElementById('effect').addEventListener('change', function() {
            document.getElementById('hidden-effect').value = this.value;
        });

        // استدعاء الوظيفة عند تغيير نوع العملية
        document.getElementById('transaction_type_id').addEventListener('change', updateEffectValue);

        // تأكد من استدعاء الوظيفة عند تحميل الصفحة لضبط القيم المبدئية
        window.addEventListener('load', updateEffectValue);


        // استدعاء الوظيفة عند تحميل الصفحة لضبط القيم المبدئية
        // updateEffectValue();


        ////////////////////////////////////////////////////////////////////////
        //   تعديل ظهور المستودع الثانوي عند التحويل المخزني
        document.addEventListener("DOMContentLoaded", function() {
            // تحديد العناصر المطلوبة
            const transactionTypeSelect = document.getElementById("transaction_type_id");
            const secondaryWarehouseContainer = document.getElementById("secondary_warehouse_container");

            // تعريف الدالة المسؤولة عن إظهار أو إخفاء المستودع الثانوي
            function toggleSecondaryWarehouse() {
                const selectedValue = transactionTypeSelect.value;
                secondaryWarehouseContainer.style.display = selectedValue === "5" ? "block" : "none";
            }

            // ربط الحدث بالتغيير واستدعاء الدالة عند التحميل
            transactionTypeSelect.addEventListener("change", toggleSecondaryWarehouse);
            toggleSecondaryWarehouse(); // للتحقق من القيمة الافتراضية عند تحميل الصفحة
        });
        ////////////////////////////////////////////////////////////////////////////////
        // الاحتفاظ بالقيم السابقة
        // توليد معرف فريد عند بدء عملية جديدة
        function generateTransactionId() {
            let transactionId = localStorage.getItem('currentTransactionId');
            if (!transactionId) {
                transactionId = 'txn_' + Date.now();
                localStorage.setItem('currentTransactionId', transactionId);
            }
            return transactionId;
        }
        // حفظ المنتجات مع ربطها بمعرف العملية
        function saveTransactionProducts() {
            const transactionId = generateTransactionId();
            const rows = Array.from(document.querySelectorAll('.product-row'));

            const products = rows.map(row => ({
                product: row.querySelector('[name="products[]"]')?.value || '',
                unit: row.querySelector('[name="units[]"]')?.value || '',
                quantity: row.querySelector('[name="quantities[]"]')?.value || '',
                unitPrice: row.querySelector('[name="unit_prices[]"]')?.value || '',
                total: row.querySelector('[name="totals[]"]')?.value || '',
                location: row.querySelector('[name="warehouse_locations[]"]')?.value || ''
            }));

            console.log(products);
        }

        // استعادة المنتجات عند تحميل الصفحة
        function loadTransactionProducts() {
            const transactionId = localStorage.getItem('currentTransactionId');
            if (!transactionId) return;

            const transactions = JSON.parse(localStorage.getItem('transactions')) || {};
            const products = transactions[transactionId] || [];

            products.forEach(product => {
                addProductRow();
                const lastRow = document.querySelector('.product-row:last-child');
                lastRow.querySelector('[name="products[]"]').value = product.product;
                populateUnits(lastRow, product.product, product.unit);
                lastRow.querySelector('[name="quantities[]"]').value = product.quantity;
                lastRow.querySelector('[name="unit_prices[]"]').value = product.unitPrice;
                lastRow.querySelector('[name="totals[]"]').value = product.total;
                lastRow.querySelector('[name="warehouse_locations[]"]').value = product.location;
            });
        }

        // استدعاء عند تحميل الصفحة
        window.addEventListener('load', loadTransactionProducts);

        // حفظ المنتجات عند التغيير
        document.addEventListener('change', saveTransactionProducts);
    </script>

</x-layout>
