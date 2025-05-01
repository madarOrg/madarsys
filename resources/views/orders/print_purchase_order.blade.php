<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أمر شراء #{{ $order->purchase_order_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-section {
            text-align: left;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>أمر شراء</h1>
            <p>رقم: {{ $order->purchase_order_number }}</p>
            <p>التاريخ: {{ $order->created_at->format('Y-m-d') }}</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>معلومات الشركة</h3>
                <p>اسم الشركة: {{ $order->branch->name ?? 'الشركة الرئيسية' }}</p>
                <p>العنوان: {{ $order->branch->address ?? 'عنوان الشركة' }}</p>
                <p>الهاتف: {{ $order->branch->phone ?? 'رقم الهاتف' }}</p>
            </div>
            <div class="info-box">
                <h3>معلومات المورد</h3>
                <p>اسم المورد: {{ $order->partner->name ?? 'غير محدد' }}</p>
                <p>العنوان: {{ $order->partner->address ?? 'غير محدد' }}</p>
                <p>الهاتف: {{ $order->partner->phone ?? 'غير محدد' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($order->order_details as $index => $detail)
                    @php 
                        $itemTotal = $detail->quantity * $detail->price;
                        $total += $itemTotal;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->product->name ?? 'غير محدد' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }}</td>
                        <td>{{ number_format($itemTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: left;"><strong>الإجمالي:</strong></td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <p>توقيع المسؤول</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>توقيع المورد</p>
                <div class="signature-line"></div>
            </div>
        </div>

        <div class="footer">
            <p>ملاحظات: {{ $order->notes ?? 'لا توجد ملاحظات' }}</p>
            <p>تم إنشاء أمر الشراء هذا بواسطة نظام madarsys</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            طباعة أمر الشراء
        </button>
        <a href="{{ route('orders.pending-approval') }}" style="padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">
            العودة إلى القائمة
        </a>
    </div>
</body>
</html>
