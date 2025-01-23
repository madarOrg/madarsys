<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="border-b border-gray-900/10 pb-12">
                    <x-title :title="'تحديث بيانات المستودع'"></x-title>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
                        يرجى تحديث تفاصيل المستودع لضمان دقة البيانات.
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <x-file-input
                                id="warehouse-name"
                                name="name"
                                label="اسم المستودع"
                                type="text"
                                placeholder="اسم المستودع"
                                value="{{ old('name', $warehouse->name) }}"
                                required="true"
                            />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-file-input
                                id="warehouse-code"
                                name="code"
                                label="رمز المستودع"
                                type="text"
                                placeholder="رمز المستودع"
                                value="{{ old('code', $warehouse->code) }}"
                                required="true"
                            />
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-file-input
                                id="address"
                                name="address"
                                label="عنوان المستودع"
                                type="text"
                                placeholder="عنوان المستودع"
                                value="{{ old('address', $warehouse->address) }}"
                                required="true"
                            />
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-file-input
                                id="contact-info"
                                name="contact_info"
                                label="بيانات الاتصال"
                                type="text"
                                placeholder="رقم الهاتف أو البريد الإلكتروني"
                                value="{{ old('contact_info', $warehouse->contact_info) }}"
                                required="true"
                            />
                            @error('contact_info')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-select-dropdown
                                id="branch"
                                name="branch_id"
                                label="الفرع"
                                :options="$companies->flatMap(fn($company) => $company->branches->pluck('name', 'id'))"
                                selected="{{ old('branch_id', $warehouse->branch_id) }}"
                                required
                            />
                            @error('branch_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الحقول الإضافية الأخرى -->
                        <div class="sm:col-span-2">
                            <x-file-input
                                id="latitude"
                                name="latitude"
                                label="خط العرض"
                                type="number"
                                step="any"
                                placeholder="خط العرض"
                                value="{{ old('latitude', $warehouse->latitude) }}"
                                required="true"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <x-file-input
                                id="longitude"
                                name="longitude"
                                label="خط الطول"
                                type="number"
                                step="any"
                                placeholder="خط الطول"
                                value="{{ old('longitude', $warehouse->longitude) }}"
                                required="true"
                            />
                        </div>

                        <div class="sm:col-span-2 flex items-center space-x-2">
                            <input
                                type="checkbox"
                                id="is_smart"
                                name="is_smart"
                                value="1"
                                {{ old('is_smart', $warehouse->is_smart) ? 'checked' : '' }}
                            />
                            <label for="is-smart" class="block text-sm font-medium text-gray-600 dark:text-gray-400">
                                هل هو مستودع ذكي؟
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end px-6">
                        <x-button type="submit">تحديث</x-button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
