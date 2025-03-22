<x-layout>
    <section class="mb-24 p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        <form action="{{ route('return_suppliers.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div class="pb-6">
                    <x-title :title="'تعبئة طلب إرجاع'"></x-title>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        يرجى إدخال بيانات الطلب لضمان تنظيم العمل مع الموردين.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اختيار المورد -->
                    <div>
                        <label for="supplier_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">المورد</label>
                        <select name="supplier_id" id="supplier_id"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-500" required>
                            <option value="">اختر المورد</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- تاريخ الطلب -->
                    <div>
                        <x-file-input id="order_date" name="order_date" label="تاريخ طلب الإرجاع" type="date"
                            required="true" />
                    </div>

                    <!-- ملاحظات -->
                    <div>
                        <x-file-input id="return_reason" name="return_reason" label="ملاحظات" type="text"
                            required="true" />
                    </div>
                </div>

                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-6">تفاصيل المنتجات المرتجعة لطلب الإرجاع</h4>

                <!-- جدول العناصر -->
                <div class="overflow-x-auto">
                    <table id="invoice-items-table" class="w-full border-collapse border rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <th class="p-3">المنتج</th>
                                <th class="p-3">الكمية</th>
                                <th class="p-3">الحالة</th>
                                <th class="p-3">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- العناصر ستتم إضافتها ديناميكيًا هنا -->
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-start mt-4">
                    <x-button type="button" id="add-item"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">إضافة منتج</x-button>
                </div>

                <div class="flex justify-start mt-4">
                    <x-button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"> إرسال
                        الطلب</x-button>
                </div>
            </div>
        </form>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let products = @json($products);

            // إضافة منتج جديد إلى الجدول
            document.getElementById('add-item').addEventListener('click', function () {
                let index = document.querySelectorAll('#invoice-items-table tbody tr').length;
                let productOptions = products.map(product => `<option value="${product.id}">${product.name}</option>`).join('');
                let newRow = `
                    <tr>
                        <td class="py-2 px-4">
                            <select name="items[${index}][product_id]" class="w-full p-2 border rounded-lg" required>
                                <option value="">اختر المنتج</option>
                                ${productOptions}
                            </select>
                        </td>
                        <td class="p-3">
                            <input type="number" name="items[${index}][quantity]" class="w-full p-2 border rounded-lg" min="1" value="1" required>
                        </td>
                        <td class="p-3">
                            <select name="items[${index}][status]" class="w-full p-2 border rounded-lg" required>
                                <option value="1">منتج تالف</option>
                                <option value="2">إرسال صيانة</option>
                                <option value="3">منتهي الصلاحية</option>
                                <option value="4">إرجاع للمخزن</option>
                            </select>  
                        </td>
                        <td class="p-3">
                            <button type="button" class="remove-item text-red-600 hover:text-red-800">إزالة</button>
                        </td>
                    </tr>`;
                document.querySelector('#invoice-items-table tbody').insertAdjacentHTML('beforeend', newRow);
            });

            // إزالة عنصر من الجدول
            document.querySelector('#invoice-items-table').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>
</x-layout>
