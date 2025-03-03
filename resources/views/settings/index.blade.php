<x-layout>
    <div class="container">
        <x-title :title="'إعدادات النظام'"></x-title>
        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
            تتيح لك إعدادات النظام التحكم الكامل في الوظائف الأساسية، يرجى ضبط الإعدادات وفقًا لاحتياجات العمل لضمان تجربة مثالية للمستخدمين.
        </p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('POST')
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6 min-h-full">

                <x-file-input 
                    type="date" 
                    id="system_start_date" 
                    name="system_start_date" 
                    :label="'تاريخ بداية تشغيل النظام'" 
                    :value="old('system_start_date', $settings->where('key', 'system_start_date')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="number" 
                    id="inventory_transaction_min_date" 
                    name="inventory_transaction_min_date" 
                    :label="'أقل تاريخ مسموح للحركات المخزنية (بالأيام)'" 
                    :value="old('inventory_transaction_min_date', $settings->where('key', 'inventory_transaction_min_date')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="date" 
                    id="fiscal_year_end_date" 
                    name="fiscal_year_end_date" 
                    :label="'تاريخ نهاية السنة المالية'" 
                    :value="old('fiscal_year_end_date', $settings->where('key', 'fiscal_year_end_date')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="number" 
                    id="daily_transaction_limit" 
                    name="daily_transaction_limit" 
                    :label="'الحد الأقصى للحركات اليومية'" 
                    :value="old('daily_transaction_limit', $settings->where('key', 'daily_transaction_limit')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="number" 
                    id="minimum_items_per_transaction" 
                    name="minimum_items_per_transaction" 
                    :label="'الحد الأدنى للمنتجات في الحركة'" 
                    :value="old('minimum_items_per_transaction', $settings->where('key', 'minimum_items_per_transaction')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="number" 
                    id="max_quantity_per_product" 
                    name="max_quantity_per_product" 
                    :label="'أقصى كمية لكل منتج'" 
                    :value="old('max_quantity_per_product', $settings->where('key', 'max_quantity_per_product')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="text" 
                    id="currency" 
                    name="currency" 
                    :label="'العملة'" 
                    :value="old('currency', $settings->where('key', 'currency')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-file-input 
                    type="number" 
                    id="tax_rate" 
                    name="tax_rate" 
                    :label="'نسبة الضريبة'" 
                    :value="old('tax_rate', $settings->where('key', 'tax_rate')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <x-select-dropdown 
                    id="is_test_mode" 
                    name="is_test_mode" 
                    label="تمكين وضع الاختبار" 
                    :options="['true' => 'نعم', 'false' => 'لا']" 
                    :selected="old('is_test_mode', $settings->where('key', 'is_test_mode')->first()->value ?? '')"
                    class="flex items-center space-x-2"
                />

                <x-file-input 
                    type="number" 
                    id="max_file_size" 
                    name="max_file_size" 
                    :label="'الحد الأقصى لحجم الملفات (MB)'" 
                    :value="old('max_file_size', $settings->where('key', 'max_file_size')->first()->value ?? '')" 
                    class="flex items-center space-x-2"
                    required 
                />

                <div class="sm:col-span-2 flex justify-end">
                    <x-button type="submit">حفظ</x-button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
