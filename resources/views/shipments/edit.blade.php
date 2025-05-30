<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'تعديل الشحنة'"></x-title>
    </section>

    <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- رقم التتبع -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="shipment_number">
                    رقم التتبع:
                </label>
                <input type="text" id="shipment_number" name="shipment_number" class="form-control w-full" value="{{ old('shipment_number', $shipment->shipment_number) }}" required>
            </div>

            <!-- المنتج -->
            <div class="col-span-1">
                <label for="product_id" class="block mb-2">المنتج</label>
                <select name="product_id" id="product_id" class="w-full p-2 border rounded @error('product_id') border-red-500 @enderror" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id', $shipment->product_id) == $product->id)>
                            {{ $product->name }} (المخزن: {{ $product->quantity }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- تاريخ الشحنة -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="shipment_date">
                    تاريخ الشحنة:
                </label>
                <input type="date" id="shipment_date" name="shipment_date" class="form-control w-full" value="{{ old('shipment_date', $shipment->shipment_date) }}" required>
            </div>

            <!-- حالة الشحنة -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="status">
                    الحالة:
                </label>
                <select name="status" id="status" class="w-full p-2 border rounded @error('status') border-red-500 @enderror" required>
                    <option value="pending" @selected(old('status', $shipment->status) === 'pending')>قيد الانتظار</option>
                    <option value="shipped" @selected(old('status', $shipment->status) === 'shipped')>تم الشحن</option>
                    <option value="delivered" @selected(old('status', $shipment->status) === 'delivered')>تم التوصيل</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- الكمية -->
        <div class="col-span-1">
            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="quantity">
                الكمية:
            </label>
            <input type="number" name="quantity" id="quantity" class="form-control w-full" value="{{ old('quantity', $shipment->quantity) }}" required>
        </div>

        <div class="mt-4">
            <x-button class="bg-blue-500 text-white px-6 py-3 rounded" type="submit">
                تحديث الشحنة
            </x-button>
        </div>
    </form>
</x-layout>
