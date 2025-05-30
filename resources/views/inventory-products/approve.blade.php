
<form method="POST" action="{{ route('transactions.approve') }}">
    @csrf
    
    <div class="flex items-center gap-4">
        <div class=" flex-1 w-1/4">
                <label for="method">اختيار مبدأ السحب: </label>
    <select name="method" id="method" class="tom-select w-full">
        <option value="1">FEFO</option>
        <option value="2">FIFO</option>
        <option value="3">LIFO</option>

    </select>
</div>
    <div class="form-group flex-1 ">
        <label for="transaction_id">اختر رقم الحركة</label>
        <select name="transaction_id" id="transaction_id" class="form-control tom-select w-full" required>
            <option value="">-- اختر رقم الحركة --</option>
            @foreach ($transactions as $transaction)
                <option value="{{ $transaction->id }}">
                    رقم: {{ $transaction->id }} - التاريخ: {{ $transaction->created_at->format('Y-m-d') }} - المرجع: {{ $transaction->reference }}
                </option>
            @endforeach
        </select>
        @error('transaction_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <x-button type="submit" class="btn btn-success mt-4">اعتماد السحب</x-button>
</div>
<div id="batches-container" class="mt-4"></div>
 
</form>
<!-- نموذج طباعة الحركات -->
@include('inventory-products.partials.print', ['transactions' => $transactions])
<script>
    document.getElementById('transaction_id').addEventListener('change', function () {
        const transactionId = this.value;
        const batchesContainer = document.getElementById('batches-container');
        batchesContainer.innerHTML = '<div>جاري تحميل البيانات...</div>';

        if (transactionId) {
            fetch(`/inventory/transaction/${transactionId}/batches`)
                .then(response => response.text())
                .then(data => {
                    batchesContainer.innerHTML = data;
                })
                .catch(() => {
                    batchesContainer.innerHTML = '<div class="text-danger">حدث خطأ أثناء جلب البيانات.</div>';
                });
        } else {
            batchesContainer.innerHTML = '';
        }
    });
</script>
