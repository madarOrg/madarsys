<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <form action="{{ route('warehouses.zones.update', ['warehouse' => $zone->warehouse_id, 'zone' => $zone->id]) }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-12 mb-24">
                <div class="pb-12">
                    <x-title :title="' تعديل المنطقة التخزينية الجغرافية'" />
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">يرجى تعديل بيانات المنطقة التخزينية بدقة.</p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
                       
                        <div class="mb-2 col-span-1">
                            <label for="name" class="">اسم المنطقة</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   placeholder="أدخل اسم المنطقة"
                                   class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" 
                                   value="{{ old('name', $zone->name) }}" 
                                   required 
                            />
                        </div>

                        <div class="mb-2 col-span-1">
                            <label for="code" class="">رمز المنطقة</label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   placeholder="أدخل رمز المنطقة"
                                   class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" 
                                   value="{{ old('code', $zone->code) }}" 
                                   required 
                            />
                        </div>

                        <div class="mb-2 col-span-1">
                            <x-select-dropdown 
                                id="warehouse_id" 
                                name="warehouse_id" 
                                label="المستودع" 
                                :options="$warehouses->pluck('name', 'id')" 
                                :selected="old('warehouse_id', $zone->warehouse_id)" 
                                required
                            />
                        </div>

                        <!-- حقل السعة الكلية -->
                        <div class="mb-2 col-span-1">
                            <label for="capacity" class="">السعة الكلية</label>
                            <input type="number" 
                                   id="capacity" 
                                   name="capacity"
                                   placeholder="أدخل السعة الكلية ب(كم)" 
                                   class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" 
                                   value="{{ old('capacity', $zone->capacity) }}" 
                                   required
                            />
                        </div>

                        <!-- حقل عدد الوحدات المخزنة حاليًا -->
                        <div class="mb-2 col-span-1">
                            <label for="current_occupancy" class="">عدد الوحدات المخزنة حاليًا</label>
                            <input type="number" 
                                   id="current_occupancy" 
                                   name="current_occupancy" 
                                   placeholder="أدخل عدد الوحدات"
                                   class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" 
                                   value="{{ old('current_occupancy', $zone->current_occupancy) }}" 
                                   required
                            />
                        </div>
                          
                        <div class="mb-2 col-span-1">
                            <x-textarea 
                                id="description" 
                                name="description" 
                                label="وصف المنطقة" 
                                placeholder="أدخل وصف المنطقة"
                                :rows="2" 
                                :value="old('description', $zone->description)" 
                                required
                            />

                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-button type="submit">تعديل</x-button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-layout>
