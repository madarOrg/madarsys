<x-layout dir="rtl">
    
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إضافة طلب جديد'"></x-title>

        <!-- نموذج إضافة طلب جديد -->
        <form action="{{ route('orders.store') }}" method="POST" class="w-full mt-5">
            @csrf
            <div class="flex w-full justify-between  gap-2">
                <div class="mb-4 w-full">
                    <label for="type" class="block text-gray-700">نوع الطلب</label>
                    <select name="type" id="type"  class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="buy">شراء</option>
                        <option value="sell">بيع</option>
                    </select>
                </div>
                
                <div class="mb-4 w-full">
                    <label for="status" class="block text-gray-700">حالة الطلب</label>
                    <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="pending" @selected(old('status', $orders->status ?? '') === 'pending')>معلق</option>
                        <option value="confirmed" @selected(old('status', $orders->status ?? '') === 'confirmed')>مؤكد</option>
                        <option value="completed"@selected(old('status', $orders->status ?? '') === 'completed')>مكتمل</option>
                        <option value="canceled" @selected(old('status', $orders->status ?? '') === 'canceled')>ملغي</option>
                        {{-- <option value="pending" @selected(old('status', $orders->status ?? '') === 'shipped')>قيد الانتظار</option>
                        <option value="shipped" @selected(old('status', $orders->status ?? '') === 'shipped')>تم الشحن</option>
                        <option value="delivered" @selected(old('status', $orders->status ?? '') === 'delivered')>تم التوصيل</option> --}}
                    </select>
                </div>

                <div class="mb-4 w-full" id="partner-container">
                    <label for="partner_id" class="block text-gray-700">المورد/الشريك</label>
                    <select name="partner_id" id="partner_id" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">اختر المورد/الشريك</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" @selected(old('partner_id') == $partner->id)>{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="flex w-full justify-between  gap-2">
                <div class="mb-4 w-full">
                    <label for="order_details[0][quantity]" class="block text-gray-700">الكمية</label>
                    <input type="number" name="order_details[0][quantity]" class="w-full p-2 border border-gray-300 rounded-md"  value="{{ old('quantity') }}" min="1" required >
                        


                </div>

                <div class="mb-4 w-full">
                    <label for="order_details[0][price]" class="block text-gray-700">السعر</label>
                    <input type="number" name="order_details[0][price]" value="{{ old('price') }}"
                        class="w-full p-2 border border-gray-300 rounded-md"  step="0.01" 
                        min="0.01"  required >
                
                </div>
                <div class="mb-4 w-full">
                    <label for="payment_method" class="block text-gray-700">طريقة الدفع</label>
                    <select name="payment_type_id" id="payment_type_id"
                        class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">اختر طريقة الدفع</option>
                        @foreach($paymentTypes as $paymentType)
                            <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="order-detail w-full">
                    <label for="order_details[0][branch_id]" class="block text-gray-700">المنتج</label>
                    <select  name="branch_id" id="branch_id" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">اختر الفرع</option>
                        @foreach ($Branchs as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                            </select>
                    </select>

                    
                </div>
                <div class="order-detail w-full">
                    <label for="order_details[0][product_id]" class="block text-gray-700">المنتج</label>
                    <select name="order_details[0][product_id]" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">اختر منتجًا</option>
                        @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} (: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                    </select>

                    
                </div>

            </div>
            <!-- تفاصيل الطلب (منتجات) -->
           

          

            <!-- زر إرسال النموذج -->
            <div class="mt-12 flex justify-center">
                <button type="submit" 
                        class="bg-green-600 text-white font-bold py-4 px-10 rounded-lg shadow-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-75 transition duration-300 ease-in-out text-xl border-2 border-green-400 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> إضافة الطلب
                </button>
            </div>

        </form>
    </section>

    <!-- إضافة المنتجات Dynamically -->
    <script>
        // إضافة منتج جديد
        document.getElementById('add-product').addEventListener('click', function() {
            const orderDetails = document.getElementById('order-details');
            const productCount = orderDetails.getElementsByClassName('order-detail').length;

            const newProductHTML = `
                <div class="order-detail mt-4">
                    <label for="order_details[${productCount}][product_id]" class="block text-gray-700">المنتج</label>
                    <select name="order_details[${productCount}][product_id]" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">اختر منتجًا</option>
                        @foreach (\App\Models\Product::all() as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    
                    <div class="mb-4">
                        <label for="order_details[${productCount}][quantity]" class="block text-gray-700">الكمية</label>
                        <input type="number" name="order_details[${productCount}][quantity]" class="w-full p-2 border border-gray-300 rounded-md" required min="1">
                    </div>

                    <div class="mb-4">
                        <label for="order_details[${productCount}][price]" class="block text-gray-700">السعر</label>
                        <input type="number" name="order_details[${productCount}][price]" class="w-full p-2 border border-gray-300 rounded-md" required min="1">
                    </div>
                </div>
            `;

            orderDetails.insertAdjacentHTML('beforeend', newProductHTML);
        });
    </script>
</x-layout>
