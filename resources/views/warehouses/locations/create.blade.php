<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouse.locations.store', ['warehouse' => $warehouse->id]) }}" method="POST">
            @csrf
            <div class="space-y-12 dark:bg-gray-900 mb-24">
                <div class="pb-12">
                    <x-title :title="'إضافة موقع مستودع جديد'" />
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات موقع المستودع بدقة لضمان تنظيم البيانات
                    </p>
                    
                    <!-- شبكة توزيع الحقول -->
                    <div class="mt-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 min-h-full">
                        
                        <!-- اختيار منطقة التخزين -->
                        <div class="col-span-1">
                            <label for="storage_area_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اختر منطقة التخزين</label>
                            <select name="storage_area_id" id="storage_area_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                <option value="">-- اختر منطقة التخزين --</option>
                                @foreach($storageAreas as $area)
                                    <option value="{{ $area->id }}" {{ old('storage_area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('storage_area_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الممر (aisle) -->
                        <div class="col-span-1">
                            <x-file-input 
                                id="aisle" 
                                name="aisle" 
                                label="رقم الممر" 
                                type="text" 
                                placeholder="أدخل رقم الممر" 
                                required 
                            />
                            @error('aisle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الرف (rack) -->
                        <div class="col-span-1">
                            <x-file-input 
                                id="rack" 
                                name="rack" 
                                label="رقم الرف" 
                                type="text" 
                                placeholder="أدخل رقم الرف" 
                                required 
                            />
                            @error('rack')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الرف الفرعي (shelf) -->
                        <div class="col-span-1">
                            <x-file-input 
                                id="shelf" 
                                name="shelf" 
                                label="رقم الرف الفرعي" 
                                type="text" 
                                placeholder="أدخل رقم الرف الفرعي" 
                                required 
                            />
                            @error('shelf')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الموقع على الرف (position) -->
                        <div class="col-span-1">
                            <x-file-input 
                                id="position" 
                                name="position" 
                                label="الموقع على الرف" 
                                type="text" 
                                placeholder="حدد الموقع على الرف" 
                                required 
                            />
                            @error('position')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الباركود -->
                        <div class="col-span-1">
                            <x-file-input 
                                id="barcode" 
                                name="barcode" 
                                label="الباركود" 
                                type="text" 
                                placeholder="أدخل الباركود" 
                                required 
                            />
                            @error('barcode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- حالة الشغل (is_occupied) -->
                        <div class="col-span-1">
                            <label for="is_occupied" class="block text-sm font-medium text-gray-700 dark:text-gray-300">حالة الموقع</label>
                            <div class="mt-1 flex items-center">
                                <input type="checkbox" id="is_occupied" name="is_occupied" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">مشغول</span>
                            </div>
                            @error('is_occupied')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الملاحظات -->
                        <div class="col-span-1 sm:col-span-2">
                            <x-file-input 
                                id="notes" 
                                name="notes" 
                                label="الملاحظات" 
                                type="textarea" 
                                placeholder="أدخل الملاحظات (اختياري)" 
                            />
                            @error('notes')
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
