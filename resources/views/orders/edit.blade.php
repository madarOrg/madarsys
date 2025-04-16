<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'تعديل الطلب'"></x-title>
    </section>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- تستخدم PUT لتحديث البيانات -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- نوع الطلب -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="type">
                    نوع الطلب:
                </label>
                <select name="type" id="type" class="form-control w-full" required>
                    <option value="buy" @selected($order->type == 'buy')>شراء</option>
                    <option value="sell" @selected($order->type == 'sell')>بيع</option>
                </select>
            </div>

            <!-- حالة الطلب -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="status">
                    الحالة:
                </label>
                <select name="status" id="status" class="form-control w-full" required>
                    <option value="pending" @selected($order->status == 'pending')>قيد الانتظار</option>
                    <option value="confirmed" @selected($order->status == 'confirmed')>مؤكد</option>
                    <option value="completed" @selected($order->status == 'completed')>مكتمل</option>
                    <option value="canceled" @selected($order->status == 'canceled')>ملغي</option>
                </select>
            </div>

            <!-- اختيار الفرع -->
            <div class="col-span-1">
                <label for="branch_id" class="block mb-2">الفرع</label>
                <select name="branch_id" id="branch_id" class="form-control w-full" required>
                    @foreach($Branchs as $branch)
                        <option value="{{ $branch->id }}" @selected($order->branch_id == $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- اختيار نوع الدفع -->
            <div class="col-span-1">
                <label for="payment_type_id" class="block mb-2">نوع الدفع</label>
                <select name="payment_type_id" id="payment_type_id" class="form-control w-full" required>
                    @foreach($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" @selected($order->payment_type_id == $paymentType->id)>{{ $paymentType->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- اختيار المورد/الشريك -->
            <div class="col-span-1">
                <label for="partner_id" class="block mb-2">المورد/الشريك</label>
                <select name="partner_id" id="partner_id" class="form-control w-full">
                    <option value="">اختر المورد/الشريك</option>
                    @foreach($partners as $partner)
                        <option value="{{ $partner->id }}" @selected($order->partner_id == $partner->id)>{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- تفاصيل الطلب -->
            <div class="col-span-2 mt-4">
                <h3 class="text-lg font-medium">تفاصيل الطلب</h3>
                <div id="order_details">
                    @foreach($order->order_details as $detail)
                        <div class="flex gap-4 mt-2">
                            <select name="order_details[{{ $loop->index }}][product_id]" class="form-control w-full" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected($product->id == $detail->product_id)>{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="order_details[{{ $loop->index }}][quantity]" class="form-control w-full" value="{{ $detail->quantity }}" min="1" required>
                            <input type="number" name="order_details[{{ $loop->index }}][price]" class="form-control w-full" value="{{ $detail->price }}" min="0.01" step="0.01" required>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4">
            <x-button class="bg-blue-500 text-white px-6 py-3 rounded" type="submit">
                تحديث الطلب
            </x-button>
        </div>
    </form>
</x-layout>
