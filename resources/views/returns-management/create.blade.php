<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'إنشاء مرتجع جديد'"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <button type="submit" form="return-form" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-save ml-1"></i> حفظ المرتجع
            </button>
            <x-button href="{{ route('returns-management.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى المرتجعات
            </x-button>
        </div>
    </section>

    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <form id="return-form" action="{{ route('returns-management.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- اختيار العميل -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">العميل <span class="text-red-600">*</span></label>
                    <select id="customer_id" name="customer_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">-- اختر العميل --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- تاريخ المرتجع -->
                <div>
                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ المرتجع <span class="text-red-600">*</span></label>
                    <input type="date" id="return_date" name="return_date" value="{{ old('return_date', now()->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    @error('return_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- سبب الإرجاع -->
                <div class="md:col-span-2">
                    <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">سبب الإرجاع <span class="text-red-600">*</span></label>
                    <textarea id="return_reason" name="return_reason" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('return_reason') }}</textarea>
                    @error('return_reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- عناصر المرتجع -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">عناصر المرتجع</h3>
                
                <div id="items-container">
                    <div class="item-row bg-gray-50 p-4 rounded-md mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المنتج <span class="text-red-600">*</span></label>
                                <select name="items[0][product_id]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">-- اختر المنتج --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (المتاح: {{ $product->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية <span class="text-red-600">*</span></label>
                                <input type="number" name="items[0][quantity]" min="1" value="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">سبب إرجاع المنتج</label>
                                <input type="text" name="items[0][return_reason]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="اترك فارغاً لاستخدام سبب الإرجاع العام">
                            </div>
                        </div>
                        
                        <button type="button" class="remove-item mt-2 text-red-600 hover:text-red-800" style="display: none;">
                            <i class="fas fa-trash"></i> إزالة
                        </button>
                    </div>
                </div>
                
                <button type="button" id="add-item" class="mt-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus"></i> إضافة منتج آخر
                </button>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md">
                    <i class="fas fa-save ml-1"></i> حفظ المرتجع
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('items-container');
            const addItemButton = document.getElementById('add-item');
            let itemCount = 1;
            
            // إضافة عنصر جديد
            addItemButton.addEventListener('click', function() {
                const itemRow = document.createElement('div');
                itemRow.className = 'item-row bg-gray-50 p-4 rounded-md mb-4';
                
                const itemHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المنتج <span class="text-red-600">*</span></label>
                            <select name="items[${itemCount}][product_id]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <option value="">-- اختر المنتج --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (المتاح: {{ $product->quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكمية <span class="text-red-600">*</span></label>
                            <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">سبب إرجاع المنتج</label>
                            <input type="text" name="items[${itemCount}][return_reason]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="اترك فارغاً لاستخدام سبب الإرجاع العام">
                        </div>
                    </div>
                    
                    <button type="button" class="remove-item mt-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i> إزالة
                    </button>
                `;
                
                itemRow.innerHTML = itemHtml;
                itemsContainer.appendChild(itemRow);
                
                // إضافة حدث لزر الإزالة
                itemRow.querySelector('.remove-item').addEventListener('click', function() {
                    itemRow.remove();
                });
                
                itemCount++;
                
                // إظهار زر الإزالة للعنصر الأول إذا كان هناك أكثر من عنصر
                if (itemCount > 1) {
                    const firstRemoveButton = itemsContainer.querySelector('.item-row:first-child .remove-item');
                    if (firstRemoveButton) {
                        firstRemoveButton.style.display = 'inline-block';
                    }
                }
            });
        });
    </script>
</x-layout>
