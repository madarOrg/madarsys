<x-layout>
    <main class="relative overflow-hidden min-h-screen">
        <section class="container mx-auto px-6 py-16">
            <div class="relative">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                    <!-- صورة المنتج -->
                    <div class="md:col-span-1 flex justify-center">
                        <img src="{{ asset('storage/' . $product->image) }}"
                            class="w-full max-w-xs max-h-96 rounded-lg object-contain" alt="{{ $product->name }}">
                    </div>
                    {{-- <div class="w-full h-96 overflow-hidden bg-gray-200">
                        <img id="imagePreview" src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover rounded-md shadow">
                      </div> --}}
                      

                    <!-- بيانات المنتج -->
                    <div class="md:col-span-3">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                        <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $product->description }}</p>

                        <!-- القسم الأول من بيانات المنتج -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <p class="text-gray-700 dark:text-gray-300"><strong>الباركود:</strong>
                                {{ $product->barcode ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>كود التخزين (SKU):</strong>
                                {{ $product->sku ?? 'غير متوفر' }}</p>

                            <p class="text-gray-700 dark:text-gray-300"><strong>التصنيف:</strong>
                                {{ optional($product->category)->name ?? 'غير متوفر' }}</p>

                            <p class="text-gray-700 dark:text-gray-300"><strong>العلامة التجارية:</strong>
                                {{ $product->brand->name ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>بلد الصنع:</strong>
                                {{ $product->manufacturingCountry->name ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>المكونات:</strong>
                                {{ $product->ingredients ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>المورد:</strong>
                                {{ optional($product->supplier)->name ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>الكمية المتوفرة:</strong>
                                {{ $product->stock_quantity }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>سعر الشراء:</strong>
                                ${{ number_format($product->purchase_price, 2) }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>سعر البيع:</strong>
                                ${{ number_format($product->selling_price, 2) }}</p>

                            <p class="text-gray-700 dark:text-gray-300"><strong>الحد الأدنى للمخزون:</strong>
                                {{ $product->min_stock_level }} {{ $product->unit->name}}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>الحد الأقصى للمخزون:</strong>
                                {{ $product->max_stock_level }} {{ $product->unit->name}}</p>
                        </div>

                        <!-- القسم الثاني من بيانات المنتج -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <p class="text-gray-700 dark:text-gray-300"><strong>الوحدة:</strong>
                                {{ optional($product->unit)->name ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>الضريبة (%):</strong>
                                {{ $product->tax ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>التخفيض (%):</strong>
                                {{ $product->discount ?? 'غير متوفر' }}</p>

                            <p class="text-gray-700 dark:text-gray-300"><strong>تاريخ الشراء:</strong>
                                {{ $product->purchase_date ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>تاريخ الإنتاج:</strong>
                                {{ $product->manufacturing_date ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>تاريخ الانتهاء:</strong>
                                {{ $product->expiration_date ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>آخر تحديث:</strong>
                                {{ $product->updated_at ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>الملاحظات :</strong>
                                {{ $product->notes ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>الحالة:</strong>
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-md {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->is_active ? 'متاح' : 'غير متاح' }}
                                </span>
                            </p>
                        </div>

                        <div class="mt-6">
                            <a href="javascript:void(0)"
                                class="py-2 px-4 rounded-lg bg-gray-700 text-white hover:bg-gray-600"
                                onclick="closeModalAndGoBack()">
                                إغلاق
                            </a>
                        </div>
                    </div>
                </div>
        </section>
    </main>

</x-layout>
<script>
    function closeModalAndGoBack() {
        const modal = document.getElementById('product-modal');
        if (modal) {
            modal.style.display = 'none'; // إخفاء المودال
        }
        window.history.back(); // العودة إلى الصفحة السابقة
    }
    function previewImage(event) {
    var output = document.getElementById('imagePreview');
    if (event.target.files && event.target.files[0]) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = 'block';  // تأكد من أن القيمة تغيرت لـ block
        output.onload = function() {
            URL.revokeObjectURL(output.src); // تحرير الذاكرة
        }
    } else {
        output.src = '';
        output.style.display = 'none';
    }
}

</script>
