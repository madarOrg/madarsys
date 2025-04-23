<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title title="إنشاء مرتجع مورد جديد"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <x-button type="submit" form="supplier-return-form" class="">
                <i class="fas fa-save ml-1"></i> حفظ المرتجع
            </x-button>
            <x-button href="{{ route('returns-suppliers.index') }}" class="">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى مرتجعات الموردين
            </x-button>
        </div>
    </section>

    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <form id="supplier-return-form" action="{{ route('returns-suppliers.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- اختيار المورد -->
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">المورد <span class="text-red-600">*</span></label>
                    <select id="supplier_id" name="supplier_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">-- اختر المورد --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- تاريخ المرتجع -->
                <div>
                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ المرتجع <span class="text-red-600">*</span></label>
                    <input type="date" id="return_date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    @error('return_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- حالة المرتجع -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة المرتجع <span class="text-red-600">*</span></label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                    @error('status')
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
                                <select name="items[0][product_id]" class="product-select tom-select w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">-- اختر المنتج --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}">
                                            {{ $product->name }}- {{ $product->barcode }}- {{ $product->sku }} (المتاح: {{ $product->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('items.0.product_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية <span class="text-red-600">*</span></label>
                                <input type="number" name="items[0][quantity]" min="1" value="{{ old('items.0.quantity', 1) }}" class="quantity-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('items.0.quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="stock-warning text-red-500 text-xs mt-1 hidden">الكمية المدخلة أكبر من المتاح في المخزون!</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">سبب إرجاع المنتج</label>
                                <input type="text" name="items[0][return_reason]" value="{{ old('items.0.return_reason') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="اترك فارغاً لاستخدام سبب الإرجاع العام">
                                @error('items.0.return_reason')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="button" class="remove-item mt-2 text-red-600 hover:text-red-800" style="display: none;">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </div>
                </div>
                
                <button type="button" id="add-item" class="mt-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus"></i> إضافة منتج آخر
                </button>
            </div>
            
            <div class="mt-8 flex justify-end">
                <x-button type="submit" class="">
                    <i class="fas fa-save ml-1"></i> حفظ المرتجع
                </x-button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('items-container');
            const addItemButton = document.getElementById('add-item');
            let itemCount = 1;
            
            // إضافة حدث للتحقق من الكمية المتاحة
            function addQuantityValidation(row) {
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const stockWarning = row.querySelector('.stock-warning');
                
                function validateQuantity() {
                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                    if (selectedOption.value) {
                        const availableStock = parseInt(selectedOption.dataset.stock);
                        const requestedQuantity = parseInt(quantityInput.value);
                        
                        if (requestedQuantity > availableStock) {
                            stockWarning.classList.remove('hidden');
                        } else {
                            stockWarning.classList.add('hidden');
                        }
                    }
                }
                
                productSelect.addEventListener('change', validateQuantity);
                quantityInput.addEventListener('input', validateQuantity);
            }
            
            // إضافة التحقق للصف الأول
            addQuantityValidation(document.querySelector('.item-row'));
            
            // إضافة حدث لأزرار الإزالة الموجودة
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.item-row').remove();
                    updateRemoveButtons();
                });
            });
            
            // إضافة عنصر جديد
            addItemButton.addEventListener('click', function() {
                const itemRow = document.createElement('div');
                itemRow.className = 'item-row bg-gray-50 p-4 rounded-md mb-4';
                
                const itemHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المنتج <span class="text-red-600">*</span></label>
                            <select name="items[${itemCount}][product_id]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <option value="">-- اختر المنتج --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}">
                                        {{ $product->name }} (المتاح: {{ $product->quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكمية <span class="text-red-600">*</span></label>
                            <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" class="quantity-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <p class="stock-warning text-red-500 text-xs mt-1 hidden">الكمية المدخلة أكبر من المتاح في المخزون!</p>
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
                    updateRemoveButtons();
                });
                
                // إضافة التحقق من الكمية
                addQuantityValidation(itemRow);
                
                itemCount++;
                updateRemoveButtons();
            });
            
            // تحديث أزرار الإزالة (إخفاء زر الإزالة إذا كان هناك عنصر واحد فقط)
            function updateRemoveButtons() {
                const itemRows = document.querySelectorAll('.item-row');
                if (itemRows.length === 1) {
                    itemRows[0].querySelector('.remove-item').style.display = 'none';
                } else {
                    itemRows.forEach(row => {
                        row.querySelector('.remove-item').style.display = 'inline-block';
                    });
                }
            }
        });
    </script>
</x-layout>
