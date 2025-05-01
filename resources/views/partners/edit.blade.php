<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('partners.update', $partner->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'تحديث بيانات الشريك'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى تحديث تفاصيل الشريك لضمان دقة البيانات.
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                        <div class="col-span-1">
                            <x-file-input id="partner-name" name="name" label="اسم الشريك" type="text"
                                placeholder="اسم الشريك" value="{{ old('name', $partner->name) }}" required="true" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-select-dropdown id="type" name="type" label="نوع الشريك" :options="$partnerTypes->pluck('name', 'id')"
                                :selected="$partner->type" />
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-contact_person" name="contact_person" label="اسم الشخص المسؤول"
                                type="text" placeholder="اسم الشخص المسؤول"
                                value="{{ old('contact_person', $partner->contact_person) }}" />
                            @error('contact_person')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-phone" name="phone" label="رقم الهاتف" type="text"
                                placeholder="رقم الهاتف" value="{{ old('phone', $partner->phone) }}" />
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-email" name="email" label="البريد الإلكتروني" type="email"
                                placeholder="البريد الإلكتروني" value="{{ old('email', $partner->email) }}" />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-address" name="address" label="العنوان" type="text"
                                placeholder="العنوان" value="{{ old('address', $partner->address) }}" />
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="partner-tax_number" name="tax_number" label="رقم الضريبة" type="text"
                                placeholder="رقم الضريبة" value="{{ old('tax_number', $partner->tax_number) }}" />
                            @error('tax_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1 flex items-center space-x-2">

                            <input id="partner-is_active" name="is_active" type="checkbox" value="1"
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700
                                 focus:border-indigo-500 focus:outline-none"
                                @if (old('is_active', $partner->is_active)) checked @endif />

                            <label for="partner-is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                فعال
                            </label>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6 flex justify-end mt-6">
                        <x-button type="submit">تحديث </x-button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
