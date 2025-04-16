<x-layout>
    <div class="container">
        <x-title :title="'البراندات'" class="form-title" />
        <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">
            من خلال هذه الصفحة يمكنك إضافة، تعديل أو حذف البراندات الخاصة بالمنتجات في النظام.
        </p>

        <!-- نموذج الإضافة/التعديل العلوي (يستخدم للـ "نسخ و اضافة") -->
        <form method="POST" id="brand-form" action="{{ route('brands.store') }}">
            @csrf
            <!-- حقل مخفي لتغيير طريقة الإرسال -->
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="brand_id" id="brand_id">
            <div x-data="{ open: true }">
                <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                    <span x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' : '<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'"></span>
                </button>

                <div x-show="open" x-transition>
                    <div class="flex space-x-4 gap-4">
                        <div class="form-group w-1/3">
                            <label for="name" class="block text-sm font-medium text-gray-600 dark:text-gray-400">اسم البراند</label>
                            <input type="text" name="name" id="name"
                                   class="form-control w-full bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 px-4 py-2"
                                   required>
                        </div>
                        <div class="form-group w-1/3">
                            <label for="code" class="block text-sm font-medium text-gray-600 dark:text-gray-400">الكود</label>
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

        <!-- جدول عرض البراندات -->
        <div class="overflow-x-auto mt-4">
            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">الكود</th>
                        <th class="px-4 py-3">الوصف</th>
                        <th class="px-4 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr id="brand-row-{{ $brand->id }}" class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <!-- عرض بيانات البراند -->
                            <td class="px-4 py-3" id="name-display-{{ $brand->id }}">{{ $brand->name }}</td>
                            <td class="px-4 py-3" id="code-display-{{ $brand->id }}">{{ $brand->code ?? '-' }}</td>
                            <td class="px-4 py-3" id="desc-display-{{ $brand->id }}">{{ $brand->description ?? '-' }}</td>
                            <td class="px-4 py-3 space-x-1">
                                <!-- زر النسخ والاضافة (يستخدم النموذج العلوي) -->
                                <button class="btn btn-sm btn-primary"
                                        onclick="fillBrandForm({ id: {{ $brand->id }}, name: '{{ $brand->name }}', code: '{{ $brand->code }}', description: '{{ $brand->description }}' })">
                                    نسخ و اضافة
                                </button>

                                <!-- زر التحديث المباشر (inline editing) -->
                                <button class="btn btn-sm btn-warning"
                                        onclick="inlineEdit({ id: {{ $brand->id }}, name: '{{ $brand->name }}', code: '{{ $brand->code }}', description: '{{ $brand->description }}' })">
                                    تحديث مباشر
                                </button>

                                <form method="POST" action="{{ route('brands.destroy', $brand->id) }}" class="inline">
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
function fillBrandForm(brand) {
    let form = document.getElementById('brand-form');
    // تغيير عنوان الـ action للنموذج ليصبح رابط التحديث للبراند المطلوب
    form.action = `/brands/${brand.id}`;
    
    // ضبط طريقة الإرسال إلى PUT باستخدام الحقل المخفي
    document.getElementById('form-method').value = 'PUT';
    
    // تعبئة الحقول بالقيم الموجودة للبراند
    document.getElementById('brand_id').value = brand.id;
    document.getElementById('name').value = brand.name;
    document.getElementById('code').value = brand.code || '';
    document.getElementById('description').value = brand.description || '';
    
    // تغيير نص زر الحفظ إلى "تحديث"
    document.getElementById('save-button').innerText = 'تحديث';
}

// الدالة المستخدمة للتحويل إلى وضع التعديل المباشر داخل الجدول (inline editing)
function inlineEdit(brand) {
    const row = document.getElementById(`brand-row-${brand.id}`);
    
    // إنشاء نص HTML للنموذج داخل الصف مع تضمين CSRF يدوياً
    row.innerHTML = `
        <form method="POST" action="/brands/${brand.id}" onsubmit="return inlineUpdate(event, ${brand.id})" class="w-full">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PUT">
            <td class="px-4 py-2">
                <input type="text" name="name" value="${brand.name}" class="form-control w-full bg-gray-100 dark:bg-gray-800 rounded px-2 py-1" required>
            </td>
            <td class="px-4 py-2">
                <input type="text" name="code" value="${brand.code || ''}" class="form-control w-full bg-gray-100 dark:bg-gray-800 rounded px-2 py-1">
            </td>
            <td class="px-4 py-2">
                <input type="text" name="description" value="${brand.description || ''}" class="form-control w-full bg-gray-100 dark:bg-gray-800 rounded px-2 py-1">
            </td>
            <td class="px-4 py-2 flex space-x-1">
                <button type="submit" class="btn btn-sm btn-success">حفظ</button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelInlineEdit(${brand.id}, '${brand.name}', '${brand.code}', '${brand.description}')">إلغاء</button>
            </td>
        </form>
    `;
}

// الدالة المسؤولة عن إرسال التحديث من النموذج المضمن (من داخل صف الجدول)
function inlineUpdate(event, id) {
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
function cancelInlineEdit(id, name, code, description) {
    const row = document.getElementById(`brand-row-${id}`);
    row.innerHTML = `
        <td class="px-4 py-3" id="name-display-${id}">${name}</td>
        <td class="px-4 py-3" id="code-display-${id}">${code || '-'}</td>
        <td class="px-4 py-3" id="desc-display-${id}">${description || '-'}</td>
        <td class="px-4 py-3 space-x-1">
            <button class="btn btn-sm btn-primary"
                onclick="fillBrandForm({ id: ${id}, name: '${name}', code: '${code}', description: '${description}' })">
                نسخ و اضافة
            </button>
            <button class="btn btn-sm btn-warning"
                onclick="inlineEdit({ id: ${id}, name: '${name}', code: '${code}', description: '${description}' })">
                تحديث مباشر
            </button>
            <form method="POST" action="/brands/${id}" class="inline">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
            </form>
        </td>
    `;
}

// فحص تكرار الكود قبل إرسال النموذج العلوي (المستخدم في "نسخ و اضافة" و"حفظ")
document.getElementById('brand-form').addEventListener('submit', function(event) {
    let brandCode = document.getElementById('code').value;
    let brandId = document.getElementById('brand_id').value;
    
    @foreach ($brands as $brand)
        if ('{{ $brand->code }}' === brandCode && '{{ $brand->id }}' !== brandId) {
            event.preventDefault();
            alert('الكود موجود بالفعل.');
            return;
        }
    @endforeach
});
</script>
