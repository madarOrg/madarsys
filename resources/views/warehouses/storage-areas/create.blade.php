<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouse.storage-areas.store', ['warehouse' => $warehouse->id]) }}" method="POST">
            @csrf
            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة منطقة تخزين'" />
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">يرجى إدخال بيانات منطقة التخزين بدقة لضمان تنظيم البيانات.</p>
                    
                    <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 min-h-full">
                        
                        <!-- اسم المنطقة التخزينية -->
                        <div class="col-span-1">
                            <x-file-input id="area-name" name="area_name" label="اسم المنطقة التخزينية" type="text" 
                                placeholder="أدخل اسم المنطقة" required value="{{ old('area_name') }}" />
                            @error('area_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- نوع المنطقة التخزينية -->
                        <div class="col-span-1">
                            <x-file-input id="area-type" name="area_type" label="نوع المنطقة التخزينية" type="text" 
                                placeholder="اختر نوع المنطقة" required value="{{ old('area_type') }}" />
                            @error('area_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- السعة القصوى للتخزين -->
                        <div class="col-span-1">
                            <x-file-input id="capacity" name="capacity" label="السعة القصوى للتخزين" type="number" 
                                placeholder="حدد السعة" required value="{{ old('capacity') }}" />
                            @error('capacity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- عدد المنتجات المخزنة -->
                        <div class="col-span-1">
                            <x-file-input id="current-occupancy" name="current_occupancy" label="عدد المنتجات المخزنة" type="number" 
                                placeholder="حدد العدد" required value="{{ old('current_occupancy') }}" />
                            @error('current_occupancy')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- المنطقة الفرعية -->
                        <div class="col-span-1">
                            <x-file-input id="zone-id" name="zone_id" label="المنطقة الفرعية" type="number" 
                                placeholder="حدد معرف المنطقة (اختياري)" value="{{ old('zone_id') }}" />
                            @error('zone_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- شروط التخزين -->
                        <div class="col-span-1">
                            <x-file-input id="storage-conditions" name="storage_conditions" label="شروط التخزين" type="text" 
                                placeholder="حدد شروط التخزين" required value="{{ old('storage_conditions') }}" />
                            @error('storage_conditions')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="mt-6 flex justify-end">
                        <x-button type="submit">حفظ</x-button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
