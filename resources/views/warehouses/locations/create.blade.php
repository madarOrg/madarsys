<x-layout>
    <section class="">
        <form action="{{ route('warehouses.locations.store', ['warehouse' => $warehouse->id]) }}" method="POST">
            @csrf
            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة موقع مستودع جديد'" />
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات موقع المستودع بدقة لضمان تنظيم البيانات
                    </p>

                    <!-- شبكة توزيع الحقول -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">

                        <!-- اختيار منطقة التخزين -->
                        <div class="col-span-1">
                            
                                 <x-select-dropdown 
                                        id="storage_area_id" 
                                        name="storage_area_id" 
                                        label="اختر منطقة التخزين"  
                                        :options="$storageAreas->pluck('area_name', 'id')->toArray()" 
                                        :selected="old('storage_area_id')" 
                                    />

                                
                            @error('storage_area_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الممر (aisle) -->
                        <div class="col-span-1">
                            <x-file-input id="aisle" name="aisle" label="رقم الممر" type="text"
                                placeholder="أدخل رقم الممر" required />
                            @error('aisle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الرف (rack) -->
                        <div class="col-span-1">
                            <x-file-input id="rack" name="rack" label="رقم الرف" type="text"
                                placeholder="أدخل رقم الرف" required />
                            @error('rack')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الرف الفرعي (shelf) -->
                        <div class="col-span-1">
                            <x-file-input id="shelf" name="shelf" label="رقم الرف الفرعي" type="text"
                                placeholder="أدخل رقم الرف الفرعي" required />
                            @error('shelf')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الموقع على الرف (position) -->
                        <div class="col-span-1">
                            <x-file-input id="position" name="position" label="الموقع على الرف" type="text"
                                placeholder="حدد الموقع على الرف" required />
                            @error('position')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الباركود -->
                        <div class="col-span-1">
                            <x-file-input id="barcode" name="barcode" label="الباركود" type="text"
                                placeholder="أدخل الباركود" required />
                            @error('barcode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الملاحظات -->
                        <div class="col-span-1">
                            <x-textarea id="notes" name="notes" label="الملاحظات" type="textarea"
                                placeholder="أدخل الملاحظات (اختياري)" />
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- حالة الشغل (is_occupied) -->
                        <div class="col-span-1">
                            <label for="is_occupied"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">حالة الموقع</label>
                            <div class="mt-1 flex items-center">
                                <input type="checkbox" id="is_occupied" name="is_occupied" value="1"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900  dark:border-gray-700 focus:border-indigo-500 focus:outline-none">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">مشغول</span>
                            </div>
                            @error('is_occupied')
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
