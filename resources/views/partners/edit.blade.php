<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('partners.update', $partner->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="border-b border-gray-900/10 pb-12">
                    <x-title :title="'تحديث بيانات الشريك'"></x-title>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
                        يرجى تحديث تفاصيل الشريك لضمان دقة البيانات.
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-file-input
                                id="partner-name"
                                name="name"
                                label="اسم الشريك"
                                type="text"
                                placeholder="اسم الشريك"
                                value="{{ old('name', $partner->name) }}"
                                required="true"
                            />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="type">نوع الشريك</label>
                            <select name="type" id="type" required>
                                <option value="">اختر نوع الشريك</option>
                                @foreach($partnerTypes as $type)
                                    <option value="{{ $type->name }}" {{ $partner->type == $type->name ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <x-file-input
                                id="partner-contact_person"
                                name="contact_person"
                                label="اسم الشخص المسؤول"
                                type="text"
                                placeholder="اسم الشخص المسؤول"
                                value="{{ old('contact_person', $partner->contact_person) }}"
                            />
                            @error('contact_person')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <x-file-input
                                id="partner-phone"
                                name="phone"
                                label="رقم الهاتف"
                                type="text"
                                placeholder="رقم الهاتف"
                                value="{{ old('phone', $partner->phone) }}"
                            />
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <x-file-input
                                id="partner-email"
                                name="email"
                                label="البريد الإلكتروني"
                                type="email"
                                placeholder="البريد الإلكتروني"
                                value="{{ old('email', $partner->email) }}"
                            />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <x-file-input
                                id="partner-address"
                                name="address"
                                label="العنوان"
                                type="text"
                                placeholder="العنوان"
                                value="{{ old('address', $partner->address) }}"
                            />
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <x-file-input
                                id="partner-tax_number"
                                name="tax_number"
                                label="رقم الضريبة"
                                type="text"
                                placeholder="رقم الضريبة"
                                value="{{ old('tax_number', $partner->tax_number) }}"
                            />
                            @error('tax_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    id="partner-is_active"
                                    name="is_active"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                    @if(old('is_active', $partner->is_active)) checked @endif
                                />
                                <label for="partner-is_active" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    نشط
                                </label>
                            </div>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-6 flex justify-end">
                            <x-button type="submit">تحديث الشريك</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
