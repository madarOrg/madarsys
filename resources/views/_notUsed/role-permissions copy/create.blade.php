<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('role-permissions.store') }}" method="POST">
            @csrf
            <div class="space-y-12">
                <div class="pb-12">
                    <!-- عنوان النموذج -->
                    <x-title :title="'إضافة صلاحيات إلى دور'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-white">
                        يرجى اختيار الدور والصلاحية وتحديد حالتها.
                    </p>

                    <!-- الحقول -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- اختيار الدور -->
                        <x-select-dropdown id="role_id" name="role_id" label="الدور" :options="$roles->pluck('name', 'id')" selected="{{ old('role_id') }}" required />

                        <!-- اختيار الصلاحيات -->
                        <x-select-dropdown id="permission_id" name="permission_id" label="الصلاحية" :options="$permissions->pluck('name', 'id')" selected="{{ old('permission_id', $lastActivePermission->id ?? null) }}" required />

                        <!-- اختيار حالة الصلاحية -->
                        <x-select-dropdown id="status" name="status" label="حالة الصلاحية" :options="['1' => 'فعال', '0' => 'غير فعال']" selected="{{ old('status', '1') }}" required />

                        <!-- حقل تاريخ التحديث القابل للتعديل -->
                        {{-- <label for="status_updated_at" class="block text-sm font-medium text-gray-700 dark:text-white">تاريخ التحديث</label>
                        <input type="datetime-local" id="status_updated_at" name="status_updated_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                               value="{{ old('status_updated_at', now()->format('Y-m-d\TH:i')) }}" required> --}}
                               <x-file-input type="datetime-local" id="status_updated_at" name="status_updated_at" label="تاريخ التحديث" value="{{ old('status_updated_at', now()->format('Y-m-d\TH:i')) }}" required />

                    </div>
                </div>

                <!-- زر الإرسال -->
                <div class="flex justify-end">
                    <x-button type="submit">حفظ الصلاحيات</x-button>
                </div>
            </div>
        </form>
    </section>
</x-layout>
