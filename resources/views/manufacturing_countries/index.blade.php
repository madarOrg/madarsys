<x-layout>
    <div class="container">
        <x-title :title="'الشركات المصنعة'" class="form-title" />
        <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">
            من خلال هذه الصفحة يمكنك إضافة، تعديل أو حذف الشركات المصنعة للمنتجات في النظام.
        </p>

        <!-- نموذج الإضافة/التعديل العلوي (يستخدم للـ "نسخ و اضافة") -->
        <form method="POST" id="manufacturer-form" action="{{ route('manufacturing_countries.store') }}">
            @csrf
            <!-- حقل مخفي لتغيير طريقة الإرسال -->
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="manufacturer_id" id="manufacturer_id">
            <div x-data="{ open: true }">
                <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                    <span x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' : '<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'"></span>
                </button>

                <div x-show="open" x-transition>
                    <div class="flex space-x-4 gap-4">
                        <div class="form-group w-1/3">
                            <label for="name" class="block text-sm font-medium text-gray-600 dark:text-gray-400">اسم البلد المصنع</label>
                            <input type="text" name="name" id="name"
                                   class="form-control w-full bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-4 py-2"
                                   required>
                        </div>
                        <div class="form-group w-1/3">
                            <label for="code" class="block text-sm font-medium text-gray-600 dark:text-gray-400">رمز البلد</label>
                            <input type="text" name="code" id="code"
                                   class="form-control w-full bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-4 py-2">
                        </div>
                        <div class="form-group w-1/3">
                            <label for="description" class="block text-sm font-medium text-gray-600 dark:text-gray-400">الوصف</label>
                            <input type="text" name="description" id="description"
                                   class="form-control w-full bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-4 py-2">
                        </div>
                    </div>

                    <!-- زر الحفظ من النموذج العلوي -->
                    <x-button id="save-button" type="submit" class="btn btn-success mt-2">حفظ</x-button>
                </div>
            </div>
        </form>

        <!-- جدول عرض الشركات المصنعة -->
        <div class="overflow-x-auto mt-4">
            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">رمز البلد</th>
                        <th class="px-4 py-3">الوصف</th>
                        <th class="px-4 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($manufacturingCountries as $manufacturer)
                        <tr id="manufacturer-row-{{ $manufacturer->id }}" class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <!-- عرض بيانات الشركة المصنعة -->
                            <td class="px-4 py-3" id="name-display-{{ $manufacturer->id }}">{{ $manufacturer->name }}</td>
                            <td class="px-4 py-3" id="code-display-{{ $manufacturer->id }}">{{ $manufacturer->code ?? '-' }}</td>
                            <td class="px-4 py-3" id="desc-display-{{ $manufacturer->id }}">{{ $manufacturer->description ?? '-' }}</td>
                            <td class="px-4 py-3 space-x-1">
                                <!-- زر النسخ والاضافة (يستخدم النموذج العلوي) -->
                                <button class="btn btn-sm btn-primary"
                                        onclick="fillManufacturerForm({ id: {{ $manufacturer->id }}, name: '{{ $manufacturer->name }}', code: '{{ $manufacturer->code }}', description: '{{ $manufacturer->description }}' })">
                                    نسخ و اضافة
                                </button>

                                <!-- زر التحديث المباشر (inline editing) -->
                                <button class="btn btn-sm btn-warning"
                                        onclick="inlineEditManufacturer({ id: {{ $manufacturer->id }}, name: '{{ $manufacturer->name }}', code: '{{ $manufacturer->code }}', description: '{{ $manufacturer->description }}' })">
                                    تحديث مباشر
                                </button>

                                <form method="POST" action="{{ route('manufacturing_countries.destroy', $manufacturer->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>

<!-- تعريف متغير الـ CSRF خارج الدوال لاستخدامه في نماذج التعديل المضمن -->
<script>
    const csrfToken = '{{ csrf_token() }}';
</script>

<script>
// الدالة المستخدمة لنسخ البيانات إلى النموذج العلوي للتعديل والإضافة
function fillManufacturerForm(manufacturer) {
    let form = document.getElementById('manufacturer-form');
    // تغيير عنوان الـ action للنموذج ليصبح رابط التحديث للشركة المصنعة المطلوبة
    form.action = `/manufacturing_countries/${manufacturer.id}`;
    
    // ضبط طريقة الإرسال إلى PUT باستخدام الحقل المخفي
    document.getElementById('form-method').value = 'PUT';
    
    // تعبئة الحقول بالقيم الموجودة للشركة المصنعة
    document.getElementById('manufacturer_id').value = manufacturer.id;
    document.getElementById('name').value = manufacturer.name;
    document.getElementById('code').value = manufacturer.code || '';
    document.getElementById('description').value = manufacturer.description || '';
    
    // تغيير نص زر الحفظ إلى "تحديث"
    document.getElementById('save-button').innerText = 'تحديث';
}

// الدالة المستخدمة للتحويل إلى وضع التعديل المباشر داخل الجدول (inline editing)
function inlineEditManufacturer(manufacturer) {
    const row = document.getElementById(`manufacturer-row-${manufacturer.id}`);
    
    // إنشاء نص HTML للنموذج داخل الصف مع تضمين CSRF يدوياً
    row.innerHTML = `
        <form method="POST" action="/manufacturing_countries/${manufacturer.id}" onsubmit="return inlineUpdateManufacturer(event, ${manufacturer.id})" class="w-full">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PUT">
            <td class="px-4 py-2">
                <input type="text" name="name" value="${manufacturer.name}" class="form-control w-full bg-gray-100 dark:bg-gray-800 rounded px-2 py-1" required>
            </td>
            <td class="px-4 py-2">
                <input type="text" name="code" value="${manufacturer.code || ''}" class="form-control w-full bg-gray-100 dark:bg-gray-800 rounded px-2 py-1">
            </td>
            <td class="px-4 py-2">
                <input type="text" name="description" value="${manufacturer.description || ''}" class="form-control w-full bg-gray-100 dark:bg-gray-800 rounded px-2 py-1">
            </td>
            <td class="px-4 py-2 flex space-x-1">
                <button type="submit" class="btn btn-sm btn-success">حفظ</button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelInlineEditManufacturer(${manufacturer.id}, '${manufacturer.name}', '${manufacturer.code}', '${manufacturer.description}')">إلغاء</button>
            </td>
        </form>
    `;
}

// الدالة المسؤولة عن إرسال التحديث من النموذج المضمن (من داخل صف الجدول)
function inlineUpdateManufacturer(event, id) {
    event.preventDefault(); // منع الإرسال الافتراضي للنموذج
    const form = event.target;
    const formData = new FormData(form);
    
    // إرسال الطلب باستخدام fetch
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if(response.ok) {
            // عند نجاح التحديث، إعادة تحميل الصفحة أو تحديث الصف يدويًا
            location.reload();
        } else {
            alert('حدث خطأ أثناء التحديث.');
        }
    })
    .catch(error => {
        console.error(error);
        alert('حدث خطأ أثناء التحديث.');
    });
    return false;
}

// دالة لإلغاء التعديل المباشر وإعادة عرض بيانات الصف الأصلية
function cancelInlineEditManufacturer(id, name, code, description) {
    const row = document.getElementById(`manufacturer-row-${id}`);
    row.innerHTML = `
        <td class="px-4 py-3" id="name-display-${id}">${name}</td>
        <td class="px-4 py-3" id="code-display-${id}">${code || '-'}</td>
        <td class="px-4 py-3" id="desc-display-${id}">${description || '-'}</td>
        <td class="px-4 py-3 space-x-1">
            <button class="btn btn-sm btn-primary"
                onclick="fillManufacturerForm({ id: ${id}, name: '${name}', code: '${code}', description: '${description}' })">
                نسخ و اضافة
            </button>
            <button class="btn btn-sm btn-warning"
                onclick="inlineEditManufacturer({ id: ${id}, name: '${name}', code: '${code}', description: '${description}' })">
                تحديث مباشر
            </button>
            <form method="POST" action="/manufacturing_countries/${id}" class="inline">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
            </form>
        </td>
    `;
}
</script>
