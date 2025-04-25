<form method="GET" action="{{ route('withdrawals.print') }}">
    <button type="submit" class="">طباعة</button>

    <label><input type="checkbox" name="only_unprinted" value="1"> طباعة الغير مطبوعة فقط</label>
    <label><input type="checkbox" name="mark_as_printed" value="1"> تعليم كمطبوعة بعد الطباعة</label>
</form>
