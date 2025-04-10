<x-layout>
    <section class="">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة فئة جديدة'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات الفئة الجديدة بدقة لضمان تنظيم المنتجات.
                    </p>

                    <div class="mt-6 space-y-6 ">
                        <div class="w-1/2 mx-auto">
                            <x-file-input id="name" name="name" label="اسم الفئة" type="text"
                                placeholder="اسم الفئة" required="true" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-1/2 mx-auto">
                            <x-file-input id="code" name="code" label="رمز الفئة" type="text"
                                placeholder="رمز الفئة" required="true" />
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-1/2 mx-auto">
                            <x-file-input id="description" name="description" label="وصف الفئة" type="text"
                                placeholder="وصف مختصر" required="true" />
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6 flex justify-end mt-6">
                        <x-button type="submit">حفظ </x-button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
