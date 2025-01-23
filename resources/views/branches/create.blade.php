<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf
            <div class="space-y-12">
                <div class="pb-12">
                    <!-- عنوان النموذج -->
                    <x-title :title="'إضافة مستودع جديد'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-white">
                        يرجى إدخال تفاصيل المستودع لضمان تنظيم البيانات بشكل صحيح.
                    </p>

                    <!-- الحقول -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- اسم المستودع -->
                        <x-file-input id="name" name="name" label="اسم المستودع" required placeholder="اسم المستودع" value="{{ old('name') }}" />

                        <!-- رمز المستودع -->
                        <x-file-input id="code" name="code" label="رمز المستودع" required placeholder="رمز فريد للمستودع" value="{{ old('code') }}" />

                        <!-- عنوان المستودع -->
                        <x-file-input id="address" name="address" label="عنوان المستودع" placeholder="عنوان المستودع" value="{{ old('address') }}" />

                        <!-- بيانات الاتصال -->
                        <x-textarea id="contact_info" name="contact_info" label="بيانات الاتصال" placeholder="مثل الهاتف أو البريد الإلكتروني" value="{{ old('contact_info') }}" />

                        <!-- اختيار الفرع -->
                        <x-select-dropdown id="branch_id" name="branch_id" label="الفرع" :options="$branches->pluck('name', 'id')" selected="{{ old('branch_id') }}" required />

                        <!-- اختيار المشرف -->
                        <x-select-dropdown id="supervisor_id" name="supervisor_id" label="المشرف" :options="$users->pluck('name', 'id')" selected="{{ old('supervisor_id') }}" />

                        <!-- الإحداثيات الجغرافية -->
                        <x-file-input id="latitude" name="latitude" label="خط العرض" placeholder="خط العرض" value="{{ old('latitude') }}" />
                        <x-file-input id="longitude" name="longitude" label="خط الطول" placeholder="خط الطول" value="{{ old('longitude') }}" />

                        <!-- السعة التخزينية -->
                        <x-file-input id="capacity" name="capacity" label="السعة التخزينية" placeholder="سعة المستودع" value="{{ old('capacity') }}" />

                        <!-- المساحة وعدد الأرفف -->
                        <x-file-input id="area" name="area" label="مساحة المستودع (م²)" placeholder="مساحة المستودع" value="{{ old('area') }}" />
                        <x-file-input id="shelves_count" name="shelves_count" label="عدد الأرفف" placeholder="عدد الأرفف" value="{{ old('shelves_count') }}" />

                        <!-- خيارات إضافية -->
                        <x-checkbox id="is_smart" name="is_smart" label="هل المستودع ذكي؟" :checked="old('is_smart')" />
                        <x-checkbox id="has_cctv" name="has_cctv" label="هل يحتوي على كاميرات مراقبة؟" :checked="old('has_cctv')" />
                        <x-checkbox id="has_security_system" name="has_security_system" label="هل يحتوي على نظام أمني؟" :checked="old('has_security_system')" />

                        <!-- درجة الحرارة والرطوبة -->
                        <x-file-input id="temperature" name="temperature" label="درجة الحرارة" placeholder="درجة الحرارة" value="{{ old('temperature') }}" />
                        <x-file-input id="humidity" name="humidity" label="نسبة الرطوبة" placeholder="نسبة الرطوبة" value="{{ old('humidity') }}" />
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
