<x-layout>
    <div>
        <form id="transaction-view-form" method="POST"
            action="{{ route('inventory.audit.updateTrans', $selectedTransaction->id ?? 0) }}">
            @csrf
            @method('PUT')

            <div class="p-2 rounded-lg shadow w-full overflow-x-auto">
                <div class="flex items-center justify-between mb-2">
                    <x-title :title="'تعديل الحركات المخزنية'" class="text-lg font-semibold" />
                    
                    <x-search-input id="custom-id" name="search"
                        placeholder="ابحث برقم المرجع، الشريك، القسم، أو المستودع..." :value="request()->input('search')"
                        class="w-64" />
                </div>
                
                

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                        <x-title :title="'تفاصيل الحركة'" />

                        @isset($selectedTransaction)
                            <!-- نوع العملية -->
                            <x-file-input id="transaction_type_name" name="transaction_type_name" label="نوع العملية"
                                type="text" value="{{ $selectedTransaction->transactionType->name ?? '' }}"
                                disabled="true" />

                            <!-- النوع الفرعي -->
                            <x-file-input id="sub_type_name" name="sub_type_name" label="النوع الفرعي" type="text"
                                value="{{ $selectedTransaction->subtype->name ?? '' }}" disabled="true" />
                            <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية"
                                type="datetime-local"
                                value="{{ optional($selectedTransaction->transaction_date)->format('Y-m-d\TH:i') }}"
                                disabled="true" />
                            <x-file-input id="reference" name="reference" label="الرقم المرجعي" type="text"
                                value="{{ $selectedTransaction->reference }}" readonly="true" />

                            <x-file-input id="partner_name" name="partner_name" label="الشريك" type="text"
                                value="{{ $selectedTransaction->partner->name ?? '' }}" disabled />

                            <input type="hidden" name="partner_id"
                                value="{{ old('partner_id', $selectedTransaction->partner_id ?? request('partner_id')) }}">

                            <x-file-input id="department_name" name="department_name" label="القسم" type="text"
                                value="{{ $selectedTransaction->department->name ?? '' }}" disabled="true" />
                            <x-file-input id="warehouse_name" type="text" name="warehouse_name" label="المستودع"
                                value="{{ $selectedTransaction->warehouse->name ?? '' }}" disabled />
                            <input type="hidden" name="warehouse_id"
                                value="{{ old('warehouse_id', $selectedTransaction->warehouse_id ?? request('warehouse_id')) }}" />

                            <x-file-input id="notes" name="notes" label="ملاحظات" type="textarea"
                                value="{{ $selectedTransaction->notes }}" disabled="true" />
                            <!-- الرقم المرجعي -->
                            <input type="hidden" name="reference"
                                value="{{ old('reference', $selectedTransaction->reference ?? '') }}">


                            <!-- الملاحظات -->
                            <input type="hidden" name="notes"
                                value="{{ old('notes', $selectedTransaction->notes ?? '') }}">
                        @endisset
                    </div>
                    <div class="col-span-1 md:col-span-3 p-4 rounded-lg shadow w-full overflow-x-auto">
                        <x-title :title="'تفاصيل المنتجات'" />

                        <div class="overflow-auto">
                            <table id="transaction-items"
                                class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                                <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3">المنتج</th>
                                        <th class="px-6 py-3">الوحدة</th>
                                        <th class="px-6 py-3">الكمية</th>
                                        <th class="px-6 py-3">الكمية المتوقعة</th>
                                        <th class="px-6 py-3">سعر الوحدة</th>
                                        <th class="px-6 py-3">الإجمالي</th>
                                        <th class="px-6 py-3">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($selectedTransaction)
                                        @foreach ($items as $item)
                                            <tr class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-3">{{ $item->product->name ?? '' }}</td>
                                                <td class="px-6 py-3">{{ $item->unit->name ?? '' }}</td>

                                                <!-- حقل الكمية -->
                                                <td class="px-6 py-3">
                                                    <input type="number" name="quantities[]" value="{{ $item->quantity }}"
                                                        class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                                        onchange="updateTotal(this)" />
                                                </td>
                                                  <!-- حقل الكمية -->
                                                  <td class="px-6 py-3">
                                                    <input type="number" name="quantities_expected[]" value="{{ $item->expected_audit_quantity }}"
                                                        class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                                        onchange="updateTotal(this)" />
                                                </td>

                                                <!-- حقل سعر الوحدة -->
                                                <td class="px-6 py-3">
                                                    <input type="number" name="unit_prices[]"
                                                        value="{{ $item->unit_prices }}"
                                                        class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                                        onchange="updateTotal(this)" />
                                                </td>

                                                <!-- عرض الإجمالي -->
                                                <td class="px-6 py-3 total">{{ $item->total }}</td>

                                                <!-- حقل مخفي لحفظ القيمة -->
                                                <input type="hidden" name="totals[]" value="{{ $item->total }}" />


                                                <!-- زر التحديث -->
                                                <td class="px-6 py-3">
                                                    <button type="button" class="text-red-600 hover:text-red-800"
                                                        onclick="removeProductRow(this)"> <i
                                                            class="fas fa-trash-alt"></i></button>
                                                    <a href="javascript:void(0);"
                                                        class="text-blue-600 hover:underline dark:text-blue-500"
                                                        data-item-id="{{ $item->id }}"
                                                        onclick="updateProductItem(event, this)">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endisset
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <x-pagination-links :paginator="$items" />

                            <button type="button"
                                class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7"
                                onclick="addProductRow()">إضافة منتج جديد</button>

                            <x-button type="submit">حفظ</x-button>
                        </div>

                    </div>
                </div>
            </div>
        </form>

    </div>
    <script>
        const products = @json($products); // قائمة المنتجات من Laravel
        const units = @json($units); // قائمة الوحدات من Laravel

        function addProductRow() {
            const tableBody = document.querySelector('#transaction-items tbody');
            if (!tableBody) return;

            let productOptions = products.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
            let unitOptions = units.map(u => `<option value="${u.id}">${u.name}</option>`).join('');

            const newRow = `
                <tr class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-3">
                        <select name="products[]" class="border rounded w-full">${productOptions}</select>
                    </td>
                    <td class="px-6 py-3">
                        <select name="units[]" class="border rounded w-full">${unitOptions}</select>
                    </td>
                    <td class="px-6 py-3"><input type="number" name="quantities[]" class="border rounded w-full" oninput="updateTotal(this)"/></td>
                    <td class="px-6 py-3"><input type="number" name="quantities_expected[]" class="border rounded w-full" oninput="updateTotal(this)"/></td>
                      <td class="px-6 py-3"><input type="number" name="unit_prices[]" class="border rounded w-full" oninput="updateTotal(this)"/></td>
                    <td class="px-6 py-3 total">0</td>
                    <td class="px-6 py-3">
                        <input type="hidden" name="totals[]" value="0" /> <!-- حقل مخفي للاحتفاظ بالقيمة الإجمالية -->
                        <button type="button" class="text-red-600" onclick="removeProductRow(this)">❌</button>
                    </td>
                </tr>`;

            tableBody.insertAdjacentHTML('beforeend', newRow);
        }

        function removeProductRow(button) {
            button.closest('tr').remove();
        }
        document.addEventListener("DOMContentLoaded", function() {
            // عند تحميل الصفحة، قم بتحديث الإجمالي لكل صف
            document.querySelectorAll('#transaction-items tbody tr').forEach(row => {
                updateTotal(row.querySelector('input[name="quantities[]"]'));
            });
        });

        function updateProductItem(event, button) {
            event.preventDefault();

            let row = button.closest('tr'); // البحث عن الصف
            let itemId = button.getAttribute('data-item-id');

            let quantity = row.querySelector('input[type="number"]').value || 0;
            let unitPrice = row.querySelectorAll('input[type="number"]')[1].value || 0;
            let total = row.querySelector('.total').textContent.trim();

            fetch(`/inventory/transactions/items/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: quantity,
                        unit_price: unitPrice,
                        total: total
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم تحديث المنتج بنجاح!');
                    } else {
                        alert('حدث خطأ أثناء التحديث!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }


        function updateTotal(input) {
            let row = input.closest('tr'); // البحث عن الصف الأب
            if (!row) {
                console.error("الصف غير موجود!");
                return;
            }

            let quantityInput = row.querySelector('input[name="quantities[]"]');
            let unitPriceInput = row.querySelector('input[name="unit_prices[]"]');
            let totalField = row.querySelector('.total');
            let totalInput = row.querySelector('input[name="totals[]"]');
            if (!quantityInput || !unitPriceInput || !totalField || !totalInput) {
                console.error("بعض الحقول غير موجودة في الصف:", row);
                return;
            }

            let quantity = parseFloat(quantityInput.value) || 0;
            let unitPrice = parseFloat(unitPriceInput.value) || 0;
            let totalValue = (quantity * unitPrice).toFixed(2);

            totalField.textContent = totalValue;
            totalInput.value = totalValue; // تحديث الحقل المخفي للإجمالي
        }
    </script>

</x-layout>
