<x-layout>

    <div class="container">
        <h1>إنشاء تقرير</h1>
        <form action="{{ route('reports.generate') }}" method="POST">
            @csrf
    
            <!-- اختيار النموذج -->
            <div class="form-group">
                <label for="model">اختر النموذج:</label>
                <select name="model" id="model" class="form-control" onchange="fetchFields()">
                    <option value="">اختر النموذج</option>
                    @foreach($models as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
    
            <!-- اختيار الحقول -->
            <div class="form-group">
                <label for="fields">اختر الحقول:</label>
                <div id="fields-container">
                    <p>الرجاء اختيار نموذج لعرض الحقول.</p>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary mt-3">إنشاء التقرير</button>
        </form>
    </div>
    
    </x-layout>
    
    <script>
        function fetchFields() {
            const model = document.getElementById('model').value;
            const fieldsContainer = document.getElementById('fields-container');
            fieldsContainer.innerHTML = '<p>جاري تحميل الحقول...</p>';
    
            if (!model) {
                fieldsContainer.innerHTML = '<p>الرجاء اختيار نموذج لعرض الحقول.</p>';
                return;
            }
    
            fetch("{{ route('reports.get-fields') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ model: model })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    fieldsContainer.innerHTML = `<p>${data.error}</p>`;
                    return;
                }
    
                fieldsContainer.innerHTML = '';
                Object.entries(data).forEach(([key, value]) => {
                    fieldsContainer.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="fields[]" value="${key}" id="${key}">
                            <label class="form-check-label" for="${key}">${value}</label>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                fieldsContainer.innerHTML = '<p>حدث خطأ أثناء جلب الحقول.</p>';
            });
        }
    </script>
    