<div>
    <form id="transaction-view-form">
        <!-- التقسيم الرئيسي: بيانات العملية وبيانات الأصناف -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            
            <!-- قسم بيانات العملية -->
            <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'تفاصيل الحركة'" />
                
                <label for="transaction_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">اختر الحركة</label>
                <div class="flex gap-2 mt-1">
                    <button wire:click="previousTransaction" type="button" class="bg-gray-500 text-white px-3 py-2 rounded">السابق</button>
                    <select wire:model="selectedTransactionId" id="transaction_id" class="form-select w-full">
                        @foreach ($transactions as $transaction)
                            <option value="{{ $transaction->id }}">{{ $transaction->reference }}</option>
                        @endforeach
                    </select>
                    <button wire:click="nextTransaction" type="button" class="bg-gray-500 text-white px-3 py-2 rounded">التالي</button>
                </div>
                
                <x-file-input wire:model="transaction_date" label="تاريخ العملية" type="datetime-local" disabled="true" />
                <x-file-input wire:model="reference" label="الرقم المرجعي" type="text" disabled="true" />
                <x-file-input wire:model="partner_name" label="الشريك" type="text" disabled="true" />
                <x-file-input wire:model="department_name" label="القسم" type="text" disabled="true" />
                <x-file-input wire:model="warehouse_name" label="المستودع" type="text" disabled="true" />
                <x-file-input wire:model="notes" label="ملاحظات" type="textarea" disabled="true" />
            </div>

            <!-- قسم تفاصيل الحركة -->
            <div class="col-span-1 md:col-span-3 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'تفاصيل المنتجات'" />

                <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                    <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">المنتج</th>
                            <th class="px-6 py-3">الوحدة</th>
                            <th class="px-6 py-3">الكمية</th>
                            <th class="px-6 py-3">سعر الوحدة</th>
                            <th class="px-6 py-3">الإجمالي</th>
                            <th class="px-6 py-3">موقع التخزين</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactionItems as $item)
                            <tr class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
                                <td>{{ $item['product_name'] }}</td>
                                <td>{{ $item['unit_name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ $item['unit_price'] }}</td>
                                <td>{{ $item['total'] }}</td>
                                <td>{{ $item['warehouse_location'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
