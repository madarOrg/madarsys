<div>
    <form id="transaction-view-form">
        <div class="p-4 rounded-lg shadow w-full overflow-x-auto">
            <x-title :title="'بحث عن عملية مخزنية'" />
        
            <input type="text" wire:model="search" wire:keydown.enter="searchTransactions"
                placeholder="ابحث برقم المرجع، الشريك، القسم، أو المستودع..."
                class="w-full p-2 border rounded-lg">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <!-- قسم بيانات العملية -->
            
            <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'تفاصيل الحركة'" />
                {{-- <label for="transaction_id" class="flex text-sm font-medium text-gray-600 dark:text-gray-400">اختر الحركة</label> --}}
                <div class="flex gap-2 mt-1">
                    <button wire:click.prevent="previousTransaction" type="button" class="bg-gray-500  px-3 py-2 rounded">السابق</button>
                    {{-- <select id="transaction_id" name="transaction_id" class="form-select w-full">
                        @foreach ($transactions as $transaction)
                            <option value="{{ $transaction->id }}" id="transaction_{{ $transaction->id }}"
                                {{ $selectedTransactionId == $transaction->id ? 'selected' : '' }}>
                                {{ $transaction->reference }}
                            </option>
                        @endforeach
                    </select> --}}
                    <button wire:click.prevent="nextTransaction" type="button" class="bg-gray-500  px-3 py-2 rounded">التالي</button>
                </div>
                {{-- @dd($selectedTransaction->transaction_date); --}}
                @isset($selectedTransaction)
                <x-file-input id="transaction_date" 
                        name="transaction_date" 
                        label="تاريخ العملية" 
                        type="datetime-local" 
                        value="{{ optional($selectedTransaction->transaction_date)->format('Y-m-d\TH:i') }}" 
                        disabled="true" />

                    <x-file-input id="reference" name="reference" label="الرقم المرجعي" type="text" 
                        value="{{ $selectedTransaction->reference }}" disabled="true" />

                    <x-file-input id="partner_name" name="partner_name" label="الشريك" type="text" 
                        value="{{ $selectedTransaction->partner->name ?? '' }}" disabled="true" />

                    <x-file-input id="department_name" name="department_name" label="القسم" type="text" 
                        value="{{ $selectedTransaction->department->name ?? '' }}" disabled="true" />

                    <x-file-input id="warehouse_name" name="warehouse_name" label="المستودع" type="text" 
                        value="{{ $selectedTransaction->warehouse->name ?? '' }}" disabled="true" />

                    <x-file-input id="notes" name="notes" label="ملاحظات" type="textarea" 
                        value="{{ $selectedTransaction->notes }}" disabled="true" />
                @endisset
            </div>

            <!-- قسم تفاصيل الحركة -->
            <div class="col-span-1 md:col-span-3 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'تفاصيل المنتجات'" />

                <div class="overflow-auto">
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
                            @isset($selectedTransaction)
                                @foreach($selectedTransaction->items as $item)
                                    <tr id="item_{{ $item->id }}" class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-3">{{ $item->product->name ?? '' }}</td>
                                        <td class="px-6 py-3">{{ $item->unit->name ?? '' }}</td>
                                        <td class="px-6 py-3">{{ $item->quantity }}</td>
                                        <td class="px-6 py-3">{{ $item->unit_prices }}</td>
                                        <td class="px-6 py-3">{{ $item->total }}</td>
                                        <td class="px-6 py-3">{{ $item->warehouse_location->name ?? '' }}</td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form> 
</div>
