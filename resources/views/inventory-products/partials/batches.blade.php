@foreach ($transaction->items as $item)
    <div class="mt-4 border p-3">
        <strong>المنتج:</strong> {{ $item->product->name }}
        <label for="selected_batch_{{ $transaction->id }}_{{ $item->product_id }}">
            اختر دفعة (اختياري):
        </label>
        <select name="selected_batches[{{ $item->id }}]" class="form-control">
            <option value="">-- النظام يختار تلقائيًا --</option>
            @foreach ($item->available_batches as $batch)
                <option value="{{ $batch->id }}">
                    رقم الدفعة: {{ $batch->batch_number }} | تاريخ الإنتاج: {{ $batch->production_date }} | الكمية: {{ $batch->quantity }}
                </option>
            @endforeach
        </select>
    </div>
@endforeach
