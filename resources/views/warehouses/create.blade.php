<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf

            <div class="space-y-12  dark:bg-gray-900 mb-24">
                <d class=" pb-12">
                    <x-title :title="' بيانات المستودع'"></x-title>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">يرجى إدخال تفاصيل المستودع بدقة لضمان
                        تنظيم البيانات.</p>
                    <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                        <div class="col-span-1">
                            <x-file-input id="warehouse-name" name="name" label="اسم المستودع" type="text"
                                placeholder="اسم المستودع" required="true" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="warehouse-code" name="code" label="رمز المستودع" type="text"
                                placeholder="رمز المستودع" required="true" />
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="address" name="address" label="عنوان المستودع" type="text"
                                placeholder="عنوان المستودع" required="true" />
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="contact-info" name="contact_info" label="بيانات الاتصال" type="text"
                                placeholder="رقم الهاتف أو البريد الإلكتروني" required="true" />
                            @error('contact_info')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <div class="col-span-1">
                                        <x-select-dropdown
                                            id="supervisor"
                                            name="supervisor_id"
                                            label="المشرف"
                                            :options="$users->pluck('name', 'id')"
                                            selected="{{ old('supervisor_id') }}"
                                            required
                                        />
                                        @error('supervisor_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div> --}}
                        {{--                     
                                    <div class="sm:col-span-4">
                                        <x-select-dropdown
                                            id="company"
                                            name="company_id"
                                            label="الشركة"
                                            :options="$companies->pluck('name', 'id')"
                                            selected="{{ old('company_id') }}"
                                            required
                                        />
                                        @error('company_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    --}}
                        {{-- <div class="col-span-1">
                                        <x-select-dropdown
                                            id="branch"
                                            name="branch_id"
                                            label="الفرع"
                                            :options="$companies->flatMap(fn($company) => $company->branches->pluck('name', 'id'))"
                                            selected="{{ old('branch_id') }}"
                                            required
                                        /> --}}
                        <div class="col-span-1">
                            <!-- التسمية -->
                            <label for="branch_id"
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                            <select name="branch_id" id="branch_id"
                                class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                                required>
                                @foreach ($companies as $company)
                                    @foreach ($company->branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        @error('branch_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror


                        <div class="col-span-1">
                            <x-file-input id="latitude" name="latitude" label="خط العرض" type="number" step="any"
                                placeholder="خط العرض" required="true" />
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="longitude" name="longitude" label="خط الطول" type="number" step="any"
                                placeholder="خط الطول" required="true" />
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="area" name="area" label="المساحة (متر مربع)" type="number"
                                placeholder="المساحة" required="true" />
                        </div>

                        {{-- <div class="col-span-1">
                            <x-file-input id="shelves-count" name="shelves_count" label="عدد الأرفف" type="number"
                                placeholder="10" required="true" />
                        </div> --}}

                        {{-- <div class="col-span-1">
                            <x-file-input id="capacity" name="capacity" label="السعة التخزينية (متر مكعب)"
                                type="number" placeholder="1000" required="true" />
                        </div> --}}

                        <div class="col-span-1">
                            <x-file-input id="temperature" name="temperature" label="درجة الحرارة" type="number"
                                step="any" placeholder="درجة الحرارة" required="true" />
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="humidity" name="humidity" label="الرطوبة" type="number" step="any"
                                placeholder="الرطوبة" required="true" />
                        </div>

                        <div class="col-span-1">
                            <x-file-input id="last-maintenance" name="last_maintenance" label="آخر صيانة"
                                type="date" value="2025-01-8" required="true" />
                        </div>
                    </div>
                    <div class="sm:col-span-4 flex items-center space-x-8">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active') ? 'checked' : '' }}>
                    <label for="is-active" class="block text-sm font-medium  text-gray-600 dark:text-gray-400">هل
                     المستودع
                        متاح؟</label>
                        <input type="checkbox" id="is_smart" name="is_smart" value="1"
                            {{ old('is_smart') ? 'checked' : '' }}>
                        <label for="is-smart" class="block text-sm font-medium  text-gray-600 dark:text-gray-400">هل
                            هو مستودع
                            ذكي؟</label>

                        <input type="checkbox" name="has_security_system" id="has-security-system"
                            class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm">
                        <label for="has-security-system"
                            class="block text-sm font-medium  text-gray-600 dark:text-gray-400">هل يوجد نظام
                            أمني؟</label>

                        <input type="checkbox" id="has_cctv" name="has_cctv" value="1"
                            {{ old('has_cctv') ? 'checked' : '' }}>
                        <label for="has-cctv" class="block text-sm font-medium  text-gray-600 dark:text-gray-400">هل
                            يوجد
                            CCTV؟</label>

                        <input type="checkbox" name="is_integrated_with_wms" id="is-integrated-with-wms"
                            class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm">
                        <label for="is-integrated-with-wms"
                            class="block text-sm font-medium  text-gray-600 dark:text-gray-400">هل هو مدمج مع نظام
                            إدارة المستودعات؟</label>

                        <input type="checkbox" name="has_automated_systems" id="has-automated-systems"
                            class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm">
                        <label for="has-automated-systems"
                            class="block text-sm font-medium  text-gray-600 dark:text-gray-400">هل يوجد أنظمة
                            آلية؟</label>
                    </div>

                    <div class="sm:col-span-6 flex justify-end">
                        <x-button type="submit">حفظ </x-button>
                    </div>
            </div>



            </div>


            </div>
            </div>
        </form>
    </section>
</x-layout>
<script>
    document.getElementById('company').addEventListener('change', function() {
        var selectedCompanyId = this.value;

        // إخفاء جميع الفروع
        var branchOptions = document.querySelectorAll('.branch-option');
        branchOptions.forEach(function(option) {
            option.style.display = 'none';
        });

        // عرض الفروع الخاصة بالشركة المحددة
        var filteredBranches = document.querySelectorAll('.branch-option.' + selectedCompanyId);
        filteredBranches.forEach(function(option) {
            option.style.display = 'block';
        });
    });
</script>
