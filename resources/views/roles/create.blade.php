<x-layout>
    <section class="container mx-auto py-6">
        <x-title :title="'إضافة دور جديد'" />

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            يرجى تعبئة الحقول أدناه لإنشاء دور جديد.
        </p>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- حقل اسم الدور -->
                <div class="mb-4">
                    <x-file-input 
                        id="role_name" 
                        name="name" 
                        label="اسم الدور" 
                        type="text" 
                        :value="old('name')" 
                        required />
                </div>

                <!-- حقل الحالة -->
                <x-select-dropdown 
                id="role_status"
                name="status"
                label="الحالة"
                :options="['1' => 'فعال', '0' => 'غير فعال']"
                :selected="old('status', '1')" 
          
                />

                <div class="mb-4">
                    
                    <x-textarea
                        id="description" 
                        name="description" 
                        label="ملاحظات " 
                        type="text" 
                        :value="old('description')" 
                        />
                </div>
        </div>

            <div class="flex justify-end mt-6">
                <x-button type="submit"> حفظ </x-button>
            </div>
        </form>
    </section>
</x-layout>
