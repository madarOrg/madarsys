<x-layout>
    <main class="dark:bg-gray-800 bg-white relative overflow-hidden min-h-screen">
           
        <section class="container mx-auto px-6 py-16">
            <div class="relative">
                <div class="relative flex justify-start">
                    <span class="bg-white dark:bg-gray-800 pr-3 text-lg font-medium text-gray-600 dark:text-white">
                        تفاصيل المنتج
                    </span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 mt-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full rounded-lg object-cover" alt="{{ $product->name }}">
                    </div>
                    <div class="md:w-2/3 md:pl-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                        <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $product->description }}</p>

                        <div class="mt-4">
                            <p class="text-gray-700 dark:text-gray-300"><strong>التصنيف:</strong> {{ $product->category->name }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>المورد:</strong> {{ optional($product->supplier)->name ?? 'غير متوفر' }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>سعر الشراء:</strong> ${{ number_format($product->purchase_price, 2) }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>سعر البيع:</strong> ${{ number_format($product->selling_price, 2) }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>المخزون:</strong> {{ $product->stock_quantity }} {{ $product->unit }}</p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>الحالة:</strong> 
                                <span class="px-2 py-1 text-xs font-medium rounded-md {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->is_active ? 'متاح' : 'غير متاح' }}
                                </span>
                            </p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" 
                               class="py-2 px-4 rounded-lg bg-gray-700 text-white hover:bg-gray-600">
                                الرجوع إلى القائمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layout>
