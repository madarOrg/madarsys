<x-layout>
<div class="container">
    <h2>الوحدات</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- جدول عرض الوحدات -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الوحدة الأب</th>
                <th>معامل التحويل</th>
                <th>الفرع</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $unit)
            <tr>
                <td>{{ $unit->name }}</td>
                <td>{{ $unit->parent?->name ?? '-' }}</td>
                <td>{{ $unit->conversion_factor ?? '-' }}</td>
                <td>{{ $unit->branch_id ?? '-' }}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="fillForm({{ $unit }})">تعديل</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- النموذج -->
    <h4 id="form-title">إضافة وحدة</h4>
    <form method="POST" id="unit-form" action="{{ route('units.store') }}">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">
        <input type="hidden" name="unit_id" id="unit_id">

        <div class="form-group">
            <label>الاسم</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>الوحدة الأب</label>
            <select name="parent_unit_id" id="parent_unit_id" class="form-control">
                <option value="">-- لا شيء --</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>معامل التحويل</label>
            <input type="number" step="0.0001" name="conversion_factor" id="conversion_factor" class="form-control">
        </div>

        

        <button type="submit" class="btn btn-success mt-2">حفظ</button>
    </form>
</div>

<script>
function fillForm(unit) {
    document.getElementById('unit-form').action = `/units/${unit.id}`;
    document.getElementById('form-method').value = 'POST';
    const hiddenMethod = document.createElement('input');
    hiddenMethod.type = 'hidden';
    hiddenMethod.name = '_method';
    hiddenMethod.value = 'POST';
    document.getElementById('unit-form').appendChild(hiddenMethod);

    document.getElementById('form-title').innerText = 'تعديل وحدة';
    document.getElementById('name').value = unit.name;
    document.getElementById('parent_unit_id').value = unit.parent_unit_id ?? '';
    document.getElementById('conversion_factor').value = unit.conversion_factor ?? '';
    document.getElementById('branch_id').value = unit.branch_id ?? '';
}
</script>

</x-layout>