<x-layout>

    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('branches.store') }}" method="POST">
            @csrf
            <div class="space-y-12">
                <div class="pb-12">
                    <!-- عنوان النموذج -->
                    <x-title :title="'إضافة فرع جديد'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-white">
                        يرجى إدخال تفاصيل الفرع لضمان تنظيم البيانات بشكل صحيح.
                    </p>

                    <!-- الحقول -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- اسم الفرع -->
                        <x-file-input id="name" name="name" label="اسم الفرع" required placeholder="اسم الفرع" value="{{ old('name') }}" />

                        <!-- عنوان الفرع -->
                        <x-file-input id="address" name="address" label="عنوان الفرع" placeholder="عنوان الفرع" value="{{ old('address') }}" />

                        <!-- بيانات الاتصال -->
                        <x-textarea id="contact_info" name="contact_info" label="بيانات الاتصال" placeholder="مثل الهاتف أو البريد الإلكتروني" value="{{ old('contact_info') }}" />

                        <!-- اختيار الشركة -->
                        <x-select-dropdown id="company_id" name="company_id" label="الشركة" :options="$companies->pluck('name', 'id')" selected="{{ old('company_id') }}" required />
                    </div>
                </div>

                <!-- زر الإرسال -->
                <div class="flex justify-end">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </div>
        </form>
    </section>
</x-layout>
