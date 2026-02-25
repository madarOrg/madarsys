<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'تعديل الطلب'"></x-title>
    </section>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- تستخدم PUT لتحديث البيانات -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4  border border-gray-300 p-4 rounded-md">
            <!-- نوع الطلب -->
            <div class="col-span-1">
                <x-select-dropdown id="type" name="type" label="نوع الطلب" :options="['buy' => 'شراء', 'sell' => 'بيع']" :selected="$order->type" />

            </div>

            <!-- حالة الطلب -->
            <div class="col-span-1">
                <x-select-dropdown id="status" name="status" label="حالة الطلب" :options="[
                    'pending' => 'قيد الانتظار',
                    'confirmed' => 'مؤكد',
                    'completed' => 'مكتمل',
                    'canceled' => 'ملغي',
                ]"
                    :selected="$order->status" />
                {{-- <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="status">
                    الحالة:
                </label>
                <select name="status" id="status" class="form-control w-full" required>
                    <option value="pending" @selected($order->status == 'pending')>قيد الانتظار</option>
                    <option value="confirmed" @selected($order->status == 'confirmed')>مؤكد</option>
                    <option value="completed" @selected($order->status == 'completed')>مكتمل</option>
                    <option value="canceled" @selected($order->status == 'canceled')>ملغي</option>
                </select> --}}
            </div>
            <div class="col-span-1 mt-4">
                <label for="partner_id"
                    class="text-sm font-medium text-gray-600 dark:text-gray-400">المورد/الشريك</label>
                <select name="partner_id" id="partner_id" class="form-control tom-select">
                    <option value="">اختر المورد/الشريك</option>
                    @foreach ($partners as $partner)
                        <option value="{{ $partner->id }}" @selected($order->partner_id == $partner->id)>{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>



            <!-- اختيار نوع الدفع -->
            <div class="col-span-1">
                <x-select-dropdown id="payment_type_id" name="payment_type_id" label="طريقة الدفع" :options="$paymentTypes->pluck('name', 'id')"
                    :selected="$order->payment_type_id" />
                </div>
                <!-- اختيار الفرع -->
                
         
            <div class="col-span-1 mt-4">
                <label for="warehouse_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    المستودع
                </label>
                <select name="warehouse_id" id="warehouse_id" class="form-control tom-select" required>
                    <option value="">اختر المستودع</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected($order->warehouse_id == $warehouse->id)>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- <label for="payment_type_id" class="block mb-2">نوع الدفع</label>
                <select name="payment_type_id" id="payment_type_id" class="form-control w-full" required>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" @selected($order->payment_type_id == $paymentType->id)>{{ $paymentType->name }}
                        </option>
                    @endforeach
                </select> --}}
        </div>

        <!-- اختيار المورد/الشريك -->

        <!-- تفاصيل الطلب -->
        <div class="col-span-2 mt-10  border border-gray-300 p-4 rounded-md">
            <h3 class="text-lg font-medium">تفاصيل الطلب</h3>
            <div id="order_details">
                @foreach ($order->order_details as $detail)
                    <div class="flex gap-4 mt-2  w-full">
                        <div class="w-1/3 mt-2">
                            <label for="products_id"
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">المنتج/الباركود/sku</label>

                            <select name="order_details[{{ $loop->index }}][product_id]"
                                class="form-control tom-select" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected($product->id == $detail->product_id)>
                                        {{ $product->name }} - {{ $product->barcode }}- {{ $product->sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/6">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">الوحدة</label>
                            <select name="order_details[{{ $loop->index }}][unit_id]"
                                class="form-control w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected($unit->id == $detail->product->unit_id)>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <input type="number" name="order_details[{{ $loop->index }}][quantity]"
                                class="form-control w-full" value="{{ $detail->quantity }}" min="1" required>
                            <input type="number" name="order_details[{{ $loop->index }}][price]"
                                class="form-control w-full" value="{{ $detail->price }}" min="0.01" step="0.01"
                                required> --}}

                        <div class="w-1/3">
                            <x-file-input type="number" id="quantity_{{ $loop->index }}"
                                name="order_details[{{ $loop->index }}][quantity]" label="الكمية" :value="$detail->quantity"
                                min="1" required />
                        </div>
                        <div class="w-1/3">
                            <x-file-input type="number" id="price_{{ $loop->index }}"
                                name="order_details[{{ $loop->index }}][price]" label="السعر" :value="$detail->price"
                                min="0.01" step="0.01" required />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div class="mt-4">
            <x-button class="" type="submit">
                تحديث الطلب
            </x-button>
        </div>
    </form>
</x-layout>
