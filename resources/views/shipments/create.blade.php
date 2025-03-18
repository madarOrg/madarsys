<x-layout dir="rtl">
    <!DOCTYPE html>
    <html lang="ar">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>إضافة شحنة جديدة</title>
    </head>

    <body>
        <h1>إضافة شحنة جديدة</h1>

        <form id="transaction-form" action="{{ route('shipments.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="row">
                    <div class="col-6">

                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2"
                            for="tracking_number">رقم التتبع:</label>
                        <input type="text" id="tracking_number" name="tracking_number" required><br>
                    </div>
                    <div class="col-6">

                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2"
                            for="recipient_name">اسم المستلم:</label>
                        <input class="form-control" type="text" id="recipient_name" name="recipient_name"
                            required><br>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">

                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2"
                            for="address">العنوان:</label>
                        <input type="text" id="address" name="address" required><br>
                    </div>
                    <div class="col-6">

                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2"
                            for="shipment_date">تاريخ الشحنة:</label>
                        <input type="date" id="shipment_date" name="shipment_date" required><br>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">

                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mt-2"
                            for="status">الحالة:</label>
                        <select
                            class="form-select w-full mt-1 bg-gray-100 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 focus:outline-blue-500"
                            name="status" id="status">
                            <option value="pending">قيد الانتظار</option>
                            <option value="shipped">تم الشحن</option>
                            <option value="delivered">تم التوصيل</option>
                        </select>
                    </div>
                    <div class="col-6"></div>

                </div>


                
            </div>
            <div class="row full mt-2">
                <div class="col-12">

                    <x-button class="bg-blue text-white px-4 py-2 rounded" type="submit">إضافة الشحنة</x-button>
                </div>
                <div class="col-6"></div>

            </div>
        </form>
    </body>

    </html>
</x-layout>
