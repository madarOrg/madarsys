<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إضافة شحنة جديدة'"></x-title>
    </section>

    <form action="{{ route('shipments.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- رقم التتبع -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="tracking_number">
                    رقم التتبع:
                </label>
                <input type="text" id="tracking_number" name="tracking_number" class="form-control w-full" required>
            </div>

            <!-- اسم المستلم -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="recipient_name">
                    اسم المستلم:
                </label>
                <input type="text" id="recipient_name" name="recipient_name" class="form-control w-full" required>
            </div>

            <!-- العنوان -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="address">
                    العنوان:
                </label>
                <input type="text" id="address" name="address" class="form-control w-full" required>
            </div>

            <!-- تاريخ الشحنة -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="shipment_date">
                    تاريخ الشحنة:
                </label>
                <input type="date" id="shipment_date" name="shipment_date" class="form-control w-full" required>
            </div>

            <!-- حالة الشحنة -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="status">
                    الحالة:
                </label>
                <select name="status" id="status" class="form-select w-full bg-gray-100 dark:bg-gray-800 dark:text-gray-200" required>
                    <option value="pending">قيد الانتظار</option>
                    <option value="shipped">تم الشحن</option>
                    <option value="delivered">تم التوصيل</option>
                </select>
            </div>
        </div>
            <!-- الكمية -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400" for="quantity">
                    الكمية:
                </label>
                <input type="number" name="quantity" id="quantity" class="form-control w-full" required>
            </div>
        <div class="mt-4">
            <x-button class="bg-blue-500 text-white px-6 py-3 rounded" type="submit">
                إضافة الشحنة
            </x-button>
        </div>
    </form>
</x-layout>

