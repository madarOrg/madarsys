<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('inventory.transactions.store') }}" method="POST">
            @csrf

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة حركة مخزنية جديدة'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات الحركة المخزنية الجديدة وتفاصيلها بدقة.
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                        <!-- بيانات الحركة -->
                        <div class="col-span-1">
                            <x-file-input id="transaction_type_id" name="transaction_type_id" label="نوع العملية" type="select" :options="$transactionTypes" required="true" />
                            @error('transaction_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية" type="date" required="true" />
                            @error('transaction_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="reference" name="reference" label="الرقم المرجعي" type="text" />
                            @error('reference')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner_id" name="partner_id" label="الشريك" type="select" :options="$partners" />
                            @error('partner_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="department_id" name="department_id" label="القسم" type="select" :options="$departments" />
                            @error('department_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="warehouse_id" name="warehouse_id" label="المستودع" type="select" :options="$warehouses" required="true" />
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
                            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
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
                                @foreach(old('products', []) as $index => $productId)
                                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            <select name="products[]" id="products-{{ $index }}" class="w-full">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ old('products')[$index] == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-6 py-4">
                                            <input type="number" name="quantities[]" class="w-full" value="{{ old('quantities')[$index] ?? '' }}" min="1" />
                                        </td>

                                        <td class="px-6 py-4">
                                            <input type="number" name="unit_prices[]" class="w-full" value="{{ old('unit_prices')[$index] ?? '' }}" min="0" step="0.01" />
                                        </td>

                                        <td class="px-6 py-4">
                                            <input type="number" name="totals[]" class="w-full" value="{{ old('totals')[$index] ?? '' }}" min="0" step="0.01" />
                                        </td>

                                        <td class="px-6 py-4">
                                            <select name="warehouse_locations[]" id="warehouse_locations-{{ $index }}" class="w-full">
                                                @foreach($warehouseLocations as $location)
                                                    <option value="{{ $location->id }}" {{ old('warehouse_locations')[$index] == $location->id ? 'selected' : '' }}>
                                                        {{ $location->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-6 py-4">
                                            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" class="mt-4 text-blue-600 hover:text-blue-800" onclick="addProductRow()">
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
            const table = document.querySelector('table tbody');
            const row = table.insertRow();
            row.innerHTML = `
                <td class="px-6 py-4">
                    <select name="products[]" class="w-full">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="quantities[]" class="w-full" min="1" />
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="unit_prices[]" class="w-full" min="0" step="0.01" />
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="totals[]" class="w-full" min="0" step="0.01" />
                </td>
                <td class="px-6 py-4">
                    <select name="warehouse_locations[]" class="w-full">
                        @foreach($warehouseLocations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-6 py-4">
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeProductRow(this)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;
        }

        function removeProductRow(button) {
            button.closest('tr').remove();
        }
    </script>
</x-layout>
