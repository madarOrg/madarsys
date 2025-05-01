<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة أمر شراء #{{ $purchaseOrder->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
            }
            .print-container {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid #000;
            }
            th, td {
                padding: 8px;
                text-align: right;
            }
            th {
                background-color: #f0f0f0;
            }
            .header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }
            .footer {
                margin-top: 30px;
                border-top: 1px solid #ddd;
                padding-top: 10px;
            }
            .signature {
                margin-top: 50px;
                display: flex;
                justify-content: space-between;
            }
            .signature-box {
                border-top: 1px solid #000;
                width: 200px;
                text-align: center;
                padding-top: 5px;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="no-print bg-blue-600 text-white p-4 text-center">
        <button onclick="window.print()" class="bg-white text-blue-600 px-4 py-2 rounded shadow hover:bg-gray-100">
            <i class="fas fa-print ml-1"></i> طباعة
        </button>
        <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 mr-2">
            <i class="fas fa-arrow-right ml-1"></i> العودة
        </a>
    </div>

    <div class="print-container bg-white p-8 mx-auto my-8 shadow-lg max-w-4xl">
        <!-- ترويسة الشركة -->
        <div class="header flex justify-between items-center mb-8 border-b pb-4">
            <div class="logo">
                <h1 class="text-2xl font-bold">شركة مدار</h1>
                <p>نظام إدارة المخزون والمشتريات</p>
            </div>
            <div class="document-info text-left">
                <h2 class="text-xl font-bold">أمر شراء</h2>
                <p>رقم: {{ $purchaseOrder->order_number }}</p>
                <p>التاريخ: {{ $purchaseOrder->issue_date }}</p>
            </div>
        </div>

        <!-- معلومات المورد والشركة -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="border p-4 rounded">
                <h3 class="font-bold mb-2 border-b pb-1">معلومات المورد</h3>
                <p>الاسم: {{ $purchaseOrder->partner->name ?? 'غير محدد' }}</p>
                <p>العنوان: {{ $purchaseOrder->partner->address ?? 'غير محدد' }}</p>
                <p>الهاتف: {{ $purchaseOrder->partner->phone ?? 'غير محدد' }}</p>
                <p>البريد الإلكتروني: {{ $purchaseOrder->partner->email ?? 'غير محدد' }}</p>
            </div>
            <div class="border p-4 rounded">
                <h3 class="font-bold mb-2 border-b pb-1">معلومات الشركة</h3>
                <p>الشركة: شركة مدار</p>
                <p>العنوان: {{ $purchaseOrder->order->branch->address ?? 'غير محدد' }}</p>
                <p>الهاتف: {{ $purchaseOrder->order->branch->phone ?? 'غير محدد' }}</p>
                <p>البريد الإلكتروني: info@madar.com</p>
            </div>
        </div>

        <!-- معلومات أمر الشراء -->
        <div class="mb-6">
            <h3 class="font-bold mb-2 border-b pb-1">معلومات أمر الشراء</h3>
            <div class="grid grid-cols-3 gap-4">
                <p>رقم الطلب الأصلي: {{ $purchaseOrder->order_id }}</p>
                <p>تاريخ الإصدار: {{ $purchaseOrder->issue_date }}</p>
                <p>تاريخ التسليم المتوقع: {{ $purchaseOrder->expected_delivery_date ?? 'غير محدد' }}</p>
            </div>
        </div>

        <!-- تفاصيل المنتجات -->
        <div class="mb-6">
            <h3 class="font-bold mb-2 border-b pb-1">تفاصيل المنتجات</h3>
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-center">#</th>
                        <th class="border p-2">المنتج</th>
                        <th class="border p-2 text-center">الوحدة</th>
                        <th class="border p-2 text-center">الكمية</th>
                        <th class="border p-2 text-center">السعر</th>
                        <th class="border p-2 text-center">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($purchaseOrder->order->order_details as $index => $detail)
                        @php $subtotal = $detail->quantity * $detail->price; $total += $subtotal; @endphp
                        <tr>
                            <td class="border p-2 text-center">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $detail->product->name ?? 'غير محدد' }}-{{ $detail->product->barcode ?? 'غير محدد' }}-{{ $detail->product->sku ?? 'غير محدد' }}</td>
                            <td class="border p-2 text-center">{{ $detail->unit->name ?? 'غير محدد' }}</td>
                            <td class="border p-2 text-center">{{ $detail->quantity }}</td>
                            <td class="border p-2 text-center">{{ number_format($detail->price, 2) }}</td>
                            <td class="border p-2 text-center">{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="5" class="border p-2 text-left font-bold">الإجمالي</td>
                        <td class="border p-2 text-center font-bold">{{ number_format($total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- ملاحظات -->
        @if($purchaseOrder->notes)
            <div class="mb-6">
                <h3 class="font-bold mb-2 border-b pb-1">ملاحظات</h3>
                <p class="p-2 border rounded">{{ $purchaseOrder->notes }}</p>
            </div>
        @endif

        <!-- التوقيعات -->
        <div class="signature mt-16">
            <div class="signature-box">
                <p>توقيع المورد</p>
            </div>
            <div class="signature-box">
                <p>توقيع المستلم</p>
            </div>
            <div class="signature-box">
                <p>توقيع المدير</p>
            </div>
        </div>

        <!-- الفوتر -->
        <div class="footer mt-8 text-center text-gray-500 text-sm">
            <p>تم إنشاء هذا المستند بواسطة نظام مدار لإدارة المخزون والمشتريات</p>
            <p>{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <script>
        // طباعة تلقائية عند تحميل الصفحة
        window.onload = function() {
            // انتظر ثانية واحدة ثم قم بالطباعة
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>
