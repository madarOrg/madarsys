<form action="{{ route('withdrawals.print') }}" method="GET" target="_blank">
    @if(isset($transaction_id))
        <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">
    @endif
    <button type="submit"  class="bg-blue-400 hover:bg-blue-500 text-gray-600 font-bold py-1 px-3 rounded">طباعة</button>

    <label>
        <input type="checkbox" name="only_unprinted" value="1"> طباعة الغير مطبوعة فقط
    </label>
    <label>
        <input type="checkbox" name="mark_as_printed" value="1"> تعليم كمطبوعة بعد الطباعة
    </label>
</form>
