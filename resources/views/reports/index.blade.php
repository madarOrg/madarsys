<x-layout>

    <div class="container">
        <h1>إنشاء تقرير</h1>
        <form action="{{ route('reports.generate') }}" method="POST">
            @csrf
            
            {{-- اختيار النموذج --}}
            <div class="form-group mb-3">
                <label for="model">اختر النموذج:</label>
                <select name="model" id="model" class="form-control">
                    @foreach($models as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
    
            {{-- اختيار الحقول --}}
            <div class="form-group mb-3">
                <label for="fields">اختر الحقول:</label>
                <select name="fields[]" class="form-control" multiple>
                    @foreach($fieldNames as $field => $label)
                        <option value="{{ $field }}">{{ $label }} ({{ $field }})</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">اضغط على Ctrl (أو Cmd في الماك) لاختيار أكثر من حقل.</small>
            </div>
    
            {{-- إضافة الشروط --}}
            <div class="form-group mb-3">
                <label for="conditions">أضف الشروط:</label>
                <div id="conditions">
                    <div class="condition d-flex gap-2 mb-2">
                        <select name="conditions[0][field]" class="form-control">
                            @foreach($fieldNames as $field => $label)
                                <option value="{{ $field }}">{{ $label }} ({{ $field }})</option>
                            @endforeach
                        </select>
                        <select name="conditions[0][operator]" class="form-control">
                            <option value="=">=</option>
                            <option value=">">></option>
                            <option value="<"><</option>
                            <option value="like">Like</option>
                        </select>
                        <input type="text" name="conditions[0][value]" placeholder="القيمة" class="form-control">
                    </div>
                </div>
                <button type="button" onclick="addCondition()" class="btn btn-secondary mt-2">إضافة شرط</button>
            </div>
    
            {{-- زر إنشاء التقرير --}}
            <button type="submit" class="btn btn-primary mt-3">إنشاء التقرير</button>
        </form>
    </div>
    
</x-layout>

<script>
    let conditionIndex = 1;
    function addCondition() {
        const container = document.getElementById('conditions');
        const div = document.createElement('div');
        div.classList.add('condition', 'd-flex', 'gap-2', 'mb-2');
        div.innerHTML = `
            <select name="conditions[${conditionIndex}][field]" class="form-control">
                @foreach($fieldNames as $field => $label)
                    <option value="{{ $field }}">{{ $label }} ({{ $field }})</option>
                @endforeach
            </select>
            <select name="conditions[${conditionIndex}][operator]" class="form-control">
                <option value="=">=</option>
                <option value=">">></option>
                <option value="<"><</option>
                <option value="like">Like</option>
            </select>
            <input type="text" name="conditions[${conditionIndex}][value]" placeholder="القيمة" class="form-control">
        `;
        container.appendChild(div);
        conditionIndex++;
    }
</script>
