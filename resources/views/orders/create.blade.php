<x-layout dir="rtl">
    
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إضافة طلب جديد'"></x-title>
    </section>
        <!-- نموذج إضافة طلب جديد -->
        <form action="{{ route('orders.store') }}" method="POST" class="w-full mt-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4  border border-gray-300 p-4 rounded-md">
                <!-- نوع الطلب -->
                <div class="col-span-1">
                    <label for="type" class="block text-gray-700">نوع الطلب</label>
                    <select name="type" id="type"  class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                        <option value="buy">شراء</option>
                        <option value="sell">بيع</option>
                    </select>
                </div>
                
                <div class="col-span-1">
                    <label for="status" class="block text-gray-700">حالة الطلب</label>
                    <select name="status" id="status" class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" readonly>
                        <option value="pending" @selected(old('status', $orders->status ?? '') === 'pending')>معلق</option>
                        <option value="confirmed" @selected(old('status', $orders->status ?? '') === 'confirmed')>مؤكد</option>
                        <option value="completed"@selected(old('status', $orders->status ?? '') === 'completed')>مكتمل</option>
                        <option value="canceled" @selected(old('status', $orders->status ?? '') === 'canceled')>ملغي</option>
                        {{-- <option value="pending" @selected(old('status', $orders->status ?? '') === 'shipped')>قيد الانتظار</option>
                        <option value="shipped" @selected(old('status', $orders->status ?? '') === 'shipped')>تم الشحن</option>
                        <option value="delivered" @selected(old('status', $orders->status ?? '') === 'delivered')>تم التوصيل</option> --}}
                    </select>
                </div>

                <div class="col-span-1"
                    id="partner-container mt-4">
                    <label for="partner_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">المورد/الشريك</label>
                    <select name="partner_id" id="partner_id" class="tom-select">
                        <option value="">اختر المورد/الشريك</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" @selected(old('partner_id') == $partner->id)>{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </div>
           
            <div class="col-span-1">
            <label for="payment_method" class="block text-gray-700">طريقة الدفع</label>
                <select name="payment_type_id" id="payment_type_id"
                    class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                    <option value="">اختر طريقة الدفع</option>
                    @foreach($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-1 mt-2">
            <label for="order_details[0][branch_id]" class="text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                <select  name="branch_id" id="branch_id" class="tom-select">
                    <option value="">اختر الفرع</option>
                    @foreach ($Branchs as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                        </select>
                </select>

            </div>
            </div>
            <div class="col-span-2 mt-10 border border-gray-300 p-4 rounded-md">
                <h3 class="text-lg font-medium">تفاصيل الطلب</h3>
                <div class="flex gap-4 mt-2  w-full">
                    <div class="order-detail w-1/3 mt-3">
                        <label for="order_details[0][product_id]" class="text-sm font-medium text-gray-600 dark:text-gray-400">المنتج</label>
                        <select name="order_details[0][product_id]" class="tom-select w-full ">
                            <option value="">اختر منتجًا</option>
                            @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} (: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                        </select>
    
                        
                    </div>
                    <div class="w-1/3 mt-2">
                    <label for="order_details[0][quantity]" class="block text-gray-700">الكمية</label>
                    <input type="number" name="order_details[0][quantity]" class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"  value="{{ old('quantity') }}" min="1" required >
                        


                </div>

                <div class="w-1/3 mt-2">
                    <label for="order_details[0][price]" class="block text-gray-700">السعر</label>
                    <input type="number" name="order_details[0][price]" value="{{ old('price') }}"
                        class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"  step="0.01" 
                        min="0.01"  required >
                
                </div>
              
              
            </div>
            </div>
            <!-- تفاصيل الطلب (منتجات) -->
           

          

           

        </form>
    <!-- زر إرسال النموذج -->
    <div class="mt-4">
        <x-button class="" type="submit">
             إضافة
        </x-button>
    </div>

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
