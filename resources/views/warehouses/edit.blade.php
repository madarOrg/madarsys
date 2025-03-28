<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
            @csrf

            @method('PUT')

            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="border-b border-gray-900/10 pb-12">
                    <x-title :title="'تحديث بيانات المستودع'"></x-title>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
                        يرجى تحديث تفاصيل المستودع لضمان دقة البيانات.
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
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

                        <div class="sm:col-span-2">
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

                        <div class="sm:col-span-2">
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

                        <div class="sm:col-span-2">
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

                        <div class="sm:col-span-2">
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

                        <div class="sm:col-span-2">
                            <x-file-input
                                id="capacity"
                                name="capacity"
                                label="القدرة الاستعابية (متر مربع)"
                                type="number"
                                placeholder="القدرو الاستعابية"
                                value="{{ old('capacity', $warehouse->area) }}"
                                required="true"
                            />
                        </div>

                       


                        <div class="sm:col-span-2">
                            <x-file-input
                                id="temperature"
                                name="temperature"
                                label="درجة الحرارة"
                                type="number"
                                step="any"
                                placeholder="درجة الحرارة"
                                value="{{ old('temperature', $warehouse->temperature) }}"
                                required="true"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <x-file-input
                                id="humidity"
                                name="humidity"
                                label="الرطوبة"
                                type="number"
                                step="any"
                                placeholder="الرطوبة"
                                value="{{ old('humidity', $warehouse->humidity) }}"
                                required="true"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <x-file-input
                                id="last-maintenance"
                                name="last_maintenance"
                                label="آخر صيانة"
                                type="date"
                                value="{{ old('last_maintenance', $warehouse->last_maintenance) }}"
                                required="true"
                            />
                        
                        </div>
                        {{-- checkBOX --}}
                        
                        <div class="sm:col-span-4 flex flex-wrap items-center gap-6">
                            <div class="flex items-center space-x-2">
                                
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    name="is_active" 
                                    value="1"
                                    class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    {{ old('is_active', $warehouse->is_active ?? 0) == 1 ? 'checked' : '' }}>
                                <label for="is_active" class="text-sm font-medium text-gray-600 dark:text-gray-400">هل المستودع متاح؟</label>
                                

                            </div>
                        
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    id="is_smart" 
                                    name="is_smart" 
                                    value="1"
                                    class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    {{ old('is_smart', $warehouse->is_smart) ? 'checked' : '' }}>
                                <label for="is_smart" class="text-sm font-medium text-gray-600 dark:text-gray-400">هل هو مستودع ذكي؟</label>
                            </div>
                        
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    name="has_security_system" 
                                    id="has_security_system" 
                                    value="1"
                                    class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    {{ old('has_security_system', $warehouse->has_security_system) ? 'checked' : '' }}>
                                <label for="has_security_system" class="text-sm font-medium text-gray-600 dark:text-gray-400">هل يوجد نظام أمني؟</label>
                            </div>
                        
                            <div class="flex items-center space-x-2">
                          
                                <input 
                                    type="checkbox" 
                                    id="has_cctv" 
                                    name="has_cctv" 
                                    value="1"
                                    class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    {{ old('has_cctv', $warehouse->has_cctv) ? 'checked' : '' }}>
                                <label for="has_cctv" class="text-sm font-medium text-gray-600 dark:text-gray-400">هل يوجد CCTV؟</label>
                               

                            </div>
                        
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    name="is_integrated_with_wms" 
                                    id="is_integrated_with_wms" 
                                    value="1"
                                    class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    {{ old('is_integrated_with_wms', $warehouse->is_integrated_with_wms) ? 'checked' : '' }}>
                                <label for="is_integrated_with_wms" class="text-sm font-medium text-gray-600 dark:text-gray-400">هل هو مدمج مع نظام إدارة المستودعات؟</label>
                            </div>
                        
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    name="has_automated_systems" 
                                    id="has_automated_systems" 
                                    value="1"
                                    class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    {{ old('has_automated_systems', $warehouse->has_automated_systems) ? 'checked' : '' }}>
                                <label for="has_automated_systems" class="text-sm font-medium text-gray-600 dark:text-gray-400">هل يوجد أنظمة آلية؟</label>
                           
                            </div>
                          

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
