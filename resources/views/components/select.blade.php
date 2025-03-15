@props(['name', 'options' => [], 'route' => null])

<select id="{{ $name }}" name="{{ $name }}" class="tom-select w-full" data-route="{{ $route }}">
    @foreach($options as $option)
        <option value="{{ $option['id'] }}">{{ $option['name'] ?? 'Unnamed' }}</option>
    @endforeach
</select>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectElement = document.getElementById('{{ $name }}');
        let route = selectElement.getAttribute('data-route');

        // دالة لتحديث الرابط بناءً على قيمة placeholder (مثل ${warehouseId})
        function updateRoute() {
            return route.replace(/\$\{(\w+)\}/g, function(match, paramName) {
                const element = document.getElementById(paramName);
                return element ? element.value : match;
            });
        }

        // تهيئة TomSelect باستخدام الرابط المحدّث
        function initSelect(updatedRoute) {
            if (selectElement.tomselect) {
                selectElement.tomselect.destroy();
            }
            new TomSelect(selectElement, {
                create: false,
                load: function(query, callback) {
                    if (!updatedRoute || query.length < 2) return callback();
                    fetch(`${updatedRoute}?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        callback(data.map(item => ({ value: item.id, text: item.name })));
                    })
                    .catch(() => callback());
                }
            });
        }

        // التهيئة الأولية
        let updatedRoute = updateRoute();
        initSelect(updatedRoute);

        // الاستماع لتغير اختيار المستودع وتحديث الرابط وإعادة تهيئة الحركة المخزنية
        const warehouseSelect = document.getElementById('warehouse_id');
        if (warehouseSelect) {
            warehouseSelect.addEventListener('change', function() {
                updatedRoute = updateRoute();
                initSelect(updatedRoute);
            });
        }
    });
</script>
