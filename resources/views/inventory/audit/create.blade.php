<x-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">إضافة عملية جرد جديدة</h1>
        <form method="POST" action="{{ route('inventory.audit.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- كود الجرد (يمكن توليده تلقائيًا) -->
                <div>
                    <label for="inventory_code" class="block font-medium text-gray-700">كود الجرد</label>
                    <input type="text" name="inventory_code" id="inventory_code" class="form-input mt-1 block w-full" 
                           value="{{ old('inventory_code', 'AUTO_GENERATED_CODE') }}" readonly>
                </div>

                <!-- نوع الجرد -->
                <div>
                    <label for="inventory_type" class="block font-medium text-gray-700">نوع الجرد</label>
                    <select name="inventory_type" id="inventory_type" class="form-select mt-1 block w-full">
                        <option value="1" @if(old('inventory_type')==1) selected @endif>دوري</option>
                        <option value="2" @if(old('inventory_type')==2) selected @endif>مفاجئ</option>
                        <option value="3" @if(old('inventory_type')==3) selected @endif>سنوي</option>
                        <option value="4" @if(old('inventory_type')==4) selected @endif>شهري</option>
                    </select>
                </div>

                <!-- تاريخ بدء الجرد -->
                <div>
                    <label for="start_date" class="block font-medium text-gray-700">تاريخ بدء الجرد</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-input mt-1 block w-full" value="{{ old('start_date') }}">
                </div>

                <!-- تاريخ انتهاء الجرد -->
                <div>
                    <label for="end_date" class="block font-medium text-gray-700">تاريخ انتهاء الجرد</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-input mt-1 block w-full" value="{{ old('end_date') }}">
                </div>

                <!-- عدد المنتجات المتوقع جردها -->
                <div>
                    <label for="expected_products_count" class="block font-medium text-gray-700">عدد المنتجات المتوقع جردها</label>
                    <input type="number" name="expected_products_count" id="expected_products_count" class="form-input mt-1 block w-full" value="{{ old('expected_products_count') }}">
                </div>

                <!-- عدد المنتجات التي تم جردها -->
                <div>
                    <label for="counted_products_count" class="block font-medium text-gray-700">عدد المنتجات التي تم جردها</label>
                    <input type="number" name="counted_products_count" id="counted_products_count" class="form-input mt-1 block w-full" value="{{ old('counted_products_count') }}">
                </div>

                <!-- ملاحظات -->
                <div class="md:col-span-2">
                    <label for="notes" class="block font-medium text-gray-700">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" class="form-textarea mt-1 block w-full">{{ old('notes') }}</textarea>
                </div>

                <!-- المستخدمون المسؤولون عن الجرد -->
                <div class="md:col-span-2">
                    <label for="users" class="block font-medium text-gray-700">المستخدمون المسؤولون عن الجرد</label>
                    <select name="users[]" id="users" class="form-select mt-1 block w-full" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @if(collect(old('users'))->contains($user->id)) selected @endif>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- المستودعات المستهدفة -->
                <div class="md:col-span-2">
                    <label for="warehouses" class="block font-medium text-gray-700">المستودعات المستهدفة</label>
                    <select name="warehouses[]" id="warehouses" class="form-select mt-1 block w-full" multiple>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @if(collect(old('warehouses'))->contains($warehouse->id)) selected @endif>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">حفظ العملية</button>
            </div>
        </form>
    </div>
</x-layout>
