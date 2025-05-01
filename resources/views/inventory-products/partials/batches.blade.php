<div x-data="{ open: false }">
    <!-- زر لعرض المحتوى مع أيقونات التبديل -->
    <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
        <span x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'"></span>
    </button>

    <!-- المحتوى الذي سيتم إخفاءه أو إظهاره -->
    <div x-show="open" class="border overflow-auto max-h-96">
        <div class="grid grid-cols-3 gap-4 p-4">
            @foreach ($transaction->items as $item)
                <div class="border p-3 bg-white rounded-md shadow-sm">
                    <strong>المنتج:</strong> {{ $item->product->name }}
                    <label for="selected_batch_{{ $transaction->id }}_{{ $item->product_id }}">
                        اختر دفعة (اختياري):
                    </label>
                    <select name="selected_batches[{{ $item->id }}]" class="form-control w-full mt-2">
                        <option value="">-- النظام يختار تلقائيًا --</option>
                        @foreach ($item->available_batches as $batch)
                            <option value="{{ $batch->id }}">
                                رقم الدفعة: {{ $batch->batch_number }} | تاريخ الإنتاج: {{ $batch->production_date }} | الكمية: {{ $batch->quantity }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
    </div>
</div>
