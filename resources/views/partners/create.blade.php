<x-layout>
    <section class="">
        <form action="{{ route('partners.store') }}" method="POST">
            @csrf

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة شريك جديد'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات الشريك الجديد بدقة لضمان تنظيم العمل مع الشركاء.
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                        <div class="col-span-1">
                            <x-file-input id="partner-name" name="name" label="اسم الشريك" type="text"
                                placeholder="اسم الشريك" required="true" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <label for="partner-type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                نوع الشريك
                                <label for="type"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                                <select name="type" id="type"
                                    class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200
                                     hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out
                                      dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                    required>
                                    <option value="">اختر نوع الشريك</option>
                                    @foreach ($partnerTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>


                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-contact_person" name="contact_person" label="اسم الشخص المسؤول"
                                type="text" placeholder="اسم الشخص المسؤول" />
                            @error('contact_person')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-phone" name="phone" label="رقم الهاتف" type="text"
                                placeholder="رقم الهاتف" />
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-email" name="email" label="البريد الإلكتروني" type="email"
                                placeholder="البريد الإلكتروني" />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-address" name="address" label="العنوان" type="text"
                                placeholder="العنوان" />
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-tax_number" name="tax_number" label="رقم الضريبة" type="text"
                                placeholder="رقم الضريبة" />
                            @error('tax_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center space-x-2">
                            <input id="partner-is_active" name="is_active" type="checkbox" value="1"
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900  dark:border-gray-700
                                 focus:border-indigo-500 focus:outline-none">

                            <label for="partner-is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                فعال
                            </label>


                            @error('is_active')
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
