<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'تعديل موقع المستودع'" />
    </div>

    <!-- نموذج تعديل موقع المستودع -->
    <form
        action="{{ route('warehouses.locations.update', ['warehouse' => $warehouse->id, 'warehouse_location' => $warehouse_location->id]) }}"
        method="POST">
        @csrf
        @method('PUT')

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 min-h-full">
            
                       <!-- منطقة التخزين -->
                       <x-select-dropdown 
                       id="storage_area_id" 
                       name="storage_area_id" 
                       label="اختر منطقة التخزين"  
                       :options="$storageAreas->pluck('area_name', 'id')->toArray()" 
                       :selected="old('storage_area_id', $warehouse_location->storage_area_id)" 
                   />
       
            <!-- رقم الممر -->
            <x-file-input id="aisle" name="aisle" :label="'رقم الممر'" :value="old('aisle', $warehouse_location->aisle)" />

            <!-- رقم الرف -->
            <x-file-input id="rack" name="rack" :label="'رقم الرف'" :value="old('rack', $warehouse_location->rack)" />

            <!-- رقم الرف الفرعي -->
            <x-file-input id="shelf" name="shelf" :label="'رقم الرف الفرعي'" :value="old('shelf', $warehouse_location->shelf)" />

            <!-- الموقع على الرف -->
            <x-file-input id="position" name="position" :label="'الموقع على الرف'" :value="old('position', $warehouse_location->position)" />

            <!-- الباركود -->
            <x-file-input id="barcode" name="barcode" :label="'الباركود'" :value="old('barcode', $warehouse_location->barcode)" />

            <!-- الملاحظات -->
            <x-textarea id="notes" name="notes" :label="'الملاحظات'">{{ old('notes', $warehouse_location->notes) }}</x-textarea>

            <!-- الحالة: مشغول -->
            <div class="col-span-1 flex items-center space-x-2">
                <input type="checkbox" id="is_occupied" name="is_occupied" value="1"
                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:outline-none"
                    {{ $warehouse_location->is_occupied ? 'checked' : '' }}>
                <label for="is_occupied" class="text-sm text-gray-600 dark:text-gray-400">مشغول</label>
            </div>
            @error('is_occupied')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>

        <!-- زر حفظ التعديلات -->
        <div class="mt-4 flex justify-end">
            <x-button type="submit">
                تحديث
            </x-button>
        </div>
    </form>
</x-layout>
