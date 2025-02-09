<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="border-b border-gray-900/10 pb-12">
                    <x-title :title="'تحديث بيانات الفئة'"></x-title>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
                        يرجى تحديث تفاصيل الفئة لضمان دقة البيانات.
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-file-input
                                id="category-name"
                                name="name"
                                label="اسم الفئة"
                                type="text"
                                placeholder="اسم الفئة"
                                value="{{ old('name', $category->name) }}"
                                required="true"
                            />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <x-file-input
                                id="category-code"
                                name="code"
                                label="رمز الفئة"
                                type="text"
                                placeholder="رمز الفئة"
                                value="{{ old('code', $category->code) }}"
                                required="true"
                            />
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <x-textarea
                                id="description"
                                name="description"
                                label="وصف الفئة"
                                placeholder="أدخل وصف الفئة"
                                rows="4"
                            >{{ old('description', $category->description) }}</x-textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6 flex justify-end">
                            <x-button type="submit">تحديث</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
