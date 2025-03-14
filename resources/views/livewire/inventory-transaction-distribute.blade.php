<div>
    <form id="transaction-view-form">
        <div class="p-4 rounded-lg shadow w-full overflow-x-auto">
            <x-title :title="'بحث عن عملية مخزنية'" />
            <input type="text" wire:model="search" wire:keydown.enter="searchTransactions"
                placeholder="ابحث برقم المرجع، الشريك، القسم، أو المستودع..." class="w-full p-2 border rounded-lg">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <!-- قسم بيانات العملية -->
            <div class="col-span-1 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'تفاصيل الحركة'" />
                <div class="flex gap-2 mt-1">
                    <button wire:click.prevent="previousTransaction" type="button"
                        class="bg-gray-500 px-3 py-2 rounded">السابق</button>
                    <button wire:click.prevent="nextTransaction" type="button"
                        class="bg-gray-500 px-3 py-2 rounded">التالي</button>
                </div>

                @isset($selectedTransaction)
                    <x-file-input id="transaction_date" name="transaction_date" label="تاريخ العملية" type="datetime-local"
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
                <x-title :title="'توزيع منتجات الحركة'" />
                <div>
                    <!-- أزرار التنقل بين الحركات -->
                    <button wire:click="previousProduct" class="btn btn-primary">السابق</button>
                    <button wire:click="nextProduct" class="btn btn-primary">التالي</button>
                </div>
                
                <div class="overflow-auto">
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                        <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-white">
                            <tr>
                                <th class="px-6 py-3">المنتج</th>
                                <th class="px-6 py-3">الوحدة</th>
                                <th class="px-6 py-3">إجمالي الكمية</th>
                                <th class="px-6 py-3">سعر الوحدة</th>
                                <th class="px-6 py-3">إجمالي السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($selectedTransaction)
                                @foreach ($selectedTransaction->items as $item)
                                    <tr id="item_{{ $item->id }}"
                                        class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 text-gray-900 dark:text-white">
                                        <td class="px-6 py-3">{{ $item->product->name ?? '' }}</td>
                                        <td class="px-6 py-3">{{ $item->unit->name ?? '' }}</td>
                                        <td class="px-6 py-3">{{ $item->quantity }}</td>
                                        <td class="px-6 py-3">{{ $item->unit_prices }}</td>
                                        <td class="px-6 py-3">{{ $item->total }}</td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- قسم التوزيع على المناطق (جدول) -->
            <div class="col-span-2 p-4 rounded-lg shadow w-full overflow-x-auto">
                <x-title :title="'توزيع المنتجات على المناطق'" />

                <div class="overflow-auto">
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                        <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-white">
                            <tr>
                                <th class="px-6 py-3">المنتج</th>
                                <th class="px-6 py-3">المنطقة التخزينية</th>
                                <th class="px-6 py-3">موقع التخزين</th>
                                <th class="px-6 py-3">الكمية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($selectedTransaction)
                                @foreach ($selectedTransaction->items as $item)
                                    <tr id="distribution_{{ $item->id }}"
                                        class="border-b bg-gray-100 dark:bg-gray-800 dark:border-gray-700 text-gray-900 dark:text-white">

                                        <td class="px-6 py-3">{{ $item->product->name ?? '' }}</td>

                                        <!-- المنطقة التخزينية -->
                                        <td class="px-6 py-3">
                                            <select wire:model="distribution.{{ $item->id }}.storage_area_id"
                                                class="form-select bg-white text-black dark:bg-gray-700 dark:text-white">
                                                <option value="">اختر المنطقة التخزينية</option>
                                                @foreach ($selectedTransaction->warehouse->storageAreas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- موقع التخزين -->
                                        <td class="px-6 py-3">
                                            <select wire:model="distribution.{{ $item->id }}.location_id"
                                                class="form-select bg-white text-black dark:bg-gray-700 dark:text-white">
                                                <option value="">اختر موقع التخزين</option>
                                                @foreach ($selectedTransaction->warehouse->warehouseLocations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- الكمية -->
                                        <td class="px-6 py-3">
                                            <input type="number" wire:model="distribution.{{ $item->id }}.quantity"
                                                class="form-input bg-white text-black dark:bg-gray-700 dark:text-white"
                                                min="0" placeholder="الكمية">
                                        </td>
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
<script>
    document.addEventListener('livewire:load', function () {
    Livewire.on('focusTransactionDetails', function () {
        // الانتقال إلى الحقل المناسب بعد التحديث
        const firstInput = document.querySelector('#transaction_date');
        if (firstInput) {
            firstInput.focus();
        }
    });
});

</script>