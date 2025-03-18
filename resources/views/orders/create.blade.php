<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <x-title :title="'إضافة طلب جديد'"></x-title>

        <!-- نموذج إضافة طلب جديد -->
        <form action="{{ route('orders.store') }}" method="POST" class="w-full mt-5">
            @csrf
            <div class="flex w-full justify-between  gap-2">
                <div class="mb-4 w-full">
                    <label for="type" class="block text-gray-700">نوع الطلب</label>
                    <select name="type" id="type" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="buy">شراء</option>
                        <option value="sell">بيع</option>
                    </select>
                </div>
                <div class="mb-4 w-full">
                    <label for="status" class="block text-gray-700">حالة الطلب</label>
                    <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="pending">معلق</option>
                        <option value="confirmed">مؤكد</option>
                        <option value="completed">مكتمل</option>
                        <option value="canceled">ملغي</option>
                    </select>
                </div>

            </div>


            <div class="flex w-full justify-between  gap-2">
                <div class="mb-4 w-full">
                    <label for="order_details[0][quantity]" class="block text-gray-700">الكمية</label>
                    <input type="number" name="order_details[0][quantity]"
                        class="w-full p-2 border border-gray-300 rounded-md" required min="1">
                </div>

                <div class="mb-4 w-full">
                    <label for="order_details[0][price]" class="block text-gray-700">السعر</label>
                    <input type="number" name="order_details[0][price]"
                        class="w-full p-2 border border-gray-300 rounded-md" required min="1">
                   
                </div>
                <div class="mb-4 w-full">
                    <label for="payment_method" class="block text-gray-700">طريقة الدفع</label>
                    <select name="payment_method" id="payment_method"
                        class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="cash">نقدي</option>
                        <option value="cheque">شيك</option>
                    </select>
                </div>
                <div class="order-detail w-full">
                    <label for="order_details[0][product_id]" class="block text-gray-700">المنتج</label>
                    <select name="order_details[0][product_id]" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">اختر منتجًا</option>
                        @foreach (\App\Models\Product::all() as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>

                    
                </div>
            </div>
            <!-- تفاصيل الطلب (منتجات) -->
           

            <button type="button" id="add-product"
                class="bg-green-500 text-black p-2 rounded-md shadow-md hover:bg-blue-600 mb-4">
                إضافة منتج آخر
            </button>

            <!-- زر إرسال النموذج -->
            <button type="submit" class="bg-blue-500 text-black p-2 rounded-md shadow-md hover:bg-blue-600">
                إضافة الطلب
            </button>

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
