<x-layout>
    <div class="container">
        <x-title :title="' إعدادات النظام'"></x-title>
        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
            تتيح لك إعدادات النظام التحكم الكامل في الوظائف الأساسية, يرجى ضبط الإعدادات وفقًا لاحتياجات العمل لضمان تجربة مثالية للمستخدمين
        </p>
        
    
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('POST') 
     <!-- تاريخ بداية تشغيل النظام -->
     <div class="mb-3">
        <label for="system_start_date" class="form-label">تاريخ بداية تشغيل النظام</label>
        <input type="date" id="system_start_date" name="system_start_date"
               class="form-control" value="{{ old('system_start_date', $settings->where('key', 'system_start_date')->first()->value ?? '') }}" required>
    </div>
          <!-- أقل تاريخ مسموح للحركات المخزنية -->
<div class="mb-3">
    <label for="inventory_transaction_min_date" class="form-label">أقل تاريخ مسموح للحركات المخزنية (بالأيام)</label>
    <input type="number" id="inventory_transaction_min_date" name="inventory_transaction_min_date"
           class="form-control" value="{{ old('inventory_transaction_min_date', $settings->where('key', 'inventory_transaction_min_date')->first()->value ?? '') }}" required>
</div>


            <!-- تاريخ نهاية السنة المالية -->
            <div class="mb-3">
                <label for="fiscal_year_end_date" class="form-label">تاريخ نهاية السنة المالية</label>
                <input type="date" id="fiscal_year_end_date" name="fiscal_year_end_date"
                       class="form-control" value="{{ old('fiscal_year_end_date', $settings->where('key', 'fiscal_year_end_date')->first()->value ?? '') }}" required>
            </div>

            <!-- الحد الأقصى للحركات اليومية -->
            <div class="mb-3">
                <label for="daily_transaction_limit" class="form-label">الحد الأقصى للحركات اليومية</label>
                <input type="number" id="daily_transaction_limit" name="daily_transaction_limit"
                       class="form-control" value="{{ old('daily_transaction_limit', $settings->where('key', 'daily_transaction_limit')->first()->value ?? '') }}" required>
            </div>

            <!-- الحد الأدنى للمنتجات في الحركة -->
            <div class="mb-3">
                <label for="minimum_items_per_transaction" class="form-label">الحد الأدنى للمنتجات في الحركة</label>
                <input type="number" id="minimum_items_per_transaction" name="minimum_items_per_transaction"
                       class="form-control" value="{{ old('minimum_items_per_transaction', $settings->where('key', 'minimum_items_per_transaction')->first()->value ?? '') }}" required>
            </div>

            <!-- أقصى كمية لكل منتج -->
            <div class="mb-3">
                <label for="max_quantity_per_product" class="form-label">أقصى كمية لكل منتج</label>
                <input type="number" id="max_quantity_per_product" name="max_quantity_per_product"
                       class="form-control" value="{{ old('max_quantity_per_product', $settings->where('key', 'max_quantity_per_product')->first()->value ?? '') }}" required>
            </div>

            <!-- العملة -->
            <div class="mb-3">
                <label for="currency" class="form-label">العملة</label>
                <input type="text" id="currency" name="currency"
                       class="form-control" value="{{ old('currency', $settings->where('key', 'currency')->first()->value ?? '') }}" required>
            </div>

            <!-- نسبة الضريبة -->
            <div class="mb-3">
                <label for="tax_rate" class="form-label">نسبة الضريبة</label>
                <input type="number" id="tax_rate" name="tax_rate"
                       class="form-control" value="{{ old('tax_rate', $settings->where('key', 'tax_rate')->first()->value ?? '') }}" required>
            </div>

            <!-- تمكين وضع الاختبار -->
            <div class="mb-3">
                <label for="is_test_mode" class="form-label">تمكين وضع الاختبار</label>
                <select id="is_test_mode" name="is_test_mode" class="form-control">
                    <option value="true" {{ old('is_test_mode', $settings->where('key', 'is_test_mode')->first()->value ?? '') == 'true' ? 'selected' : '' }}>نعم</option>
                    <option value="false" {{ old('is_test_mode', $settings->where('key', 'is_test_mode')->first()->value ?? '') == 'false' ? 'selected' : '' }}>لا</option>
                </select>
            </div>

            <!-- الحد الأقصى لحجم الملفات -->
            <div class="mb-3">
                <label for="max_file_size" class="form-label">الحد الأقصى لحجم الملفات (MB)</label>
                <input type="number" id="max_file_size" name="max_file_size"
                       class="form-control" value="{{ old('max_file_size', $settings->where('key', 'max_file_size')->first()->value ?? '') }}" required>
            </div>
            <div class="sm:col-span-6 flex justify-end">
                <x-button type="submit">حفظ </x-button>
            </div>
            {{-- <button type="submit" class="btn btn-primary">حفظ</button> --}}
        </form>
    </div>
</x-layout>
