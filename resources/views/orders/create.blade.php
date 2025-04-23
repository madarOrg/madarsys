<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إضافة طلب جديد'"></x-title>
    </section>
    <form method="GET" action="{{ route('orders.create') }}">
      <select name="warehouse_id" onchange="this.form.submit()">
          <option value="">اختر المستودع</option>
          @foreach($warehouses as $warehouse)
              <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                  {{ $warehouse->name }}
              </option>
          @endforeach
      </select>
       <!-- نوع الطلب -->
       <div class="col-span-1">
        <label for="type" class="block text-gray-700">نوع الطلب</label>
        <select name="type" onchange="this.form.submit()" id="type" class="form-select-style w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
           hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
           duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
            <option value="buy">شراء</option>
            <option value="sell">بيع</option>
        </select>
    </div>
    </form>
    <!-- نموذج إضافة طلب جديد -->
    <form action="{{ route('orders.store') }}" method="POST" class="w-full mt-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-gray-300 p-4 rounded-md">
           

            <!-- حالة الطلب -->
            <div class="col-span-1">
                <label for="status" class="block text-gray-700">حالة الطلب</label>
                <select name="status" id="status" class="form-select-style w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" readonly>
                    <option value="pending" @selected(old('status') === 'pending')>معلق</option>
                    <option value="confirmed" @selected(old('status') === 'confirmed')>مؤكد</option>
                    <option value="completed" @selected(old('status') === 'completed')>مكتمل</option>
                    <option value="canceled" @selected(old('status') === 'canceled')>ملغي</option>
                </select>
            </div>

            <!-- الشريك -->
            <div class="col-span-1">
                <label for="partner_id"
                    class="text-sm font-medium text-gray-600 dark:text-gray-400">المورد/الشريك</label>
                <select name="partner_id" id="partner_id" class="tom-select">
                    <option value="">اختر المورد/الشريك</option>
                    @foreach ($partners as $partner)
                        <option value="{{ $partner->id }}" @selected(old('partner_id') == $partner->id)>{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- الدفع -->
            <div class="col-span-1">
                <label for="payment_method" class="block text-gray-700">طريقة الدفع</label>
                <select name="payment_type_id" id="payment_type_id" class="form-select-style w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                    <option value="">اختر طريقة الدفع</option>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="col-span-1 mt-2">
                <label for="warehouse_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                <select name="warehouse_id" id="warehouse_id" class="tom-select">
                    <option value="">اختر المستودع</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div> --}}
          
        </div>
        

        <!-- تفاصيل الطلب (المنتجات) -->
        <div id="order-details" class="col-span-2 mt-10 border border-gray-300 p-4 rounded-md">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">تفاصيل الطلب</h3>
                <button type="button" id="add-product"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded-md">
                    + إضافة منتج
                </button>
            </div>

            <!-- أول صف منتج ثابت -->
            <div class="order-detail flex gap-4">
                <div class="w-1/4">
                    <label class="text-sm font-medium text-gray-600">المنتج</label>
                    <select name="order_details[0][product_id]" class="product-select tom-select w-full">
                        <option value="">اختر منتجًا</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }} -{{ $product->barcode }} -{{ $product->sku }} (سعر: {{ $product->selling_price }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/4">
                  <label class="text-sm font-medium text-gray-600">الوحدة</label>
                  <select name="order_details[0][unit_id]" class="units-select  w-form-input-style w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                      <option value="">اختر وحدة</option>
                      @foreach ($units as $unit)
                          <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                      @endforeach
                  </select>
              </div>
                <!-- بقية الحقول: الكمية، السعر، الوحدة -->
                <div class="w-1/4">
                    <label class="block text-gray-700">الكمية</label>
                    <input type="number" name="order_details[0][quantity]" class="form-input-style w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" min="1"
                        required>
                </div>
                <div class="w-1/4">
                    <label class="block text-gray-700">السعر</label>
                    <input type="number" name="order_details[0][price]" class="form-input-style w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" step="0.01"
                        min="0.01" required>
                </div>
               
            </div>

        </div>


        <!-- زر الإرسال -->
        <div class="mt-4">
            <x-button type="submit">
                إضافة
            </x-button>
        </div>
    </form>

  
    <script type="module">
        // 1) عند تحميل الصفحة: هيّئ TomSelect على الـ selects الحالية
        document.addEventListener('DOMContentLoaded', () => {
          document.querySelectorAll('.product-select').forEach(el => {
            new TomSelect(el, { create: false, placeholder: 'اختر منتج' });
          });
          document.querySelectorAll('.units-select').forEach(el => {
            new TomSelect(el, { create: false, placeholder: 'اختر وحدة' });
          });
        });
      
        // 2) عند تغيير اختيار المنتج: جلب الوحدات وتحديث الـ units-select
        document.addEventListener('change', e => {
          if (!e.target.classList.contains('product-select')) return;
      
          const row = e.target.closest('.order-detail');
          const unitSelect = row.querySelector('.units-select');
          const productId = e.target.value;
      
          fetch(`/get-units/${productId}`)
            .then(res => res.json())
            .then(data => {
              // احصل على الـ instance الحالي (إذا موجود)
              let ts = unitSelect.tomselect;
              if (!ts) {
                // إذا لم يكن موجوداً، أنشئ واحد
                ts = new TomSelect(unitSelect, {
                  create: false,
                  placeholder: 'اختر وحدة'
                });
              }
              // نظّف الخيارات القديمة ثم أضف الجديدة
              ts.clearOptions();
              data.units.forEach(u => ts.addOption({ value: u.id, text: u.name }));
              ts.refreshOptions(false);
            })
            .catch(err => console.error('Error fetching units:', err));
        });
      
        // 3) عند الضغط على زر "إضافة منتج": بناء صف جديد وتهيئة TomSelect عليه
        document.getElementById('add-product').addEventListener('click', () => {
          const container = document.getElementById('order-details');
          const i = container.getElementsByClassName('order-detail').length;
      
          const newRow = `
            <div class="order-detail flex gap-4">
              <div class="w-1/4">
                <label class="text-sm font-medium text-gray-600">المنتج</label>
                <select name="order_details[${i}][product_id]" class="product-select tom-select w-full">
                  <option value="">اختر منتجًا</option>
                  @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                      {{ $product->name }} (سعر: {{ $product->selling_price }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="w-1/4">
                <label class="block text-gray-700">الكمية</label>
                <input type="number" name="order_details[${i}][quantity]" class="form-input-style" min="1" required>
              </div>
              <div class="w-1/4">
                <label class="block text-gray-700">السعر</label>
                <input type="number" name="order_details[${i}][price]" class="form-input-style" step="0.01" min="0.01" required>
              </div>
              <div class="w-1/4">
                <label class="text-sm font-medium text-gray-600">الوحدة</label>
                <select name="order_details[${i}][unit_id]" class="units-select tom-select w-full">
                  <option value="">اختر وحدة</option>
                </select>
              </div>
            </div>
          `;
          container.insertAdjacentHTML('beforeend', newRow);
      
          // هيّئ TomSelect للحقول الجديدة
          const last = container.lastElementChild;
          new TomSelect(last.querySelector('.product-select'), { create: false, placeholder: 'اختر منتج' });
          new TomSelect(last.querySelector('.units-select'),  { create: false, placeholder: 'اختر وحدة' });
        });
      </script>
      
      
    <!-- ستايلات مبسطة -->
    <style>
        .form-select-style {
            @apply w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1;
        }

        .form-input-style {
            @apply w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1;
        }
    </style>
</x-layout>
