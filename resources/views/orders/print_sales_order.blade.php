<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أمر صرف رقم: {{ $order->purchase_order_number }}</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
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
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            color: #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: right;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-section {
            margin-top: 20px;
            text-align: left;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 30%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        .print-btn {
            text-align: center;
            margin: 20px 0;
        }
        .print-btn button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-btn button:hover {
            background-color: #2980b9;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>أمر صرف</h1>
            <p>رقم: {{ $order->purchase_order_number }}</p>
            <p>التاريخ: {{ date('Y-m-d') }}</p>
        </div>
        
        <div class="info-section">
            <div class="info-box">
                <h3>معلومات العميل</h3>
                <p><strong>الاسم:</strong> {{ $order->partner->name ?? 'غير محدد' }}</p>
                <p><strong>العنوان:</strong> {{ $order->partner->address ?? 'غير محدد' }}</p>
                <p><strong>الهاتف:</strong> {{ $order->partner->phone ?? 'غير محدد' }}</p>
            </div>
            <div class="info-box">
                <h3>معلومات الشركة</h3>
                <p><strong>الفرع:</strong> {{ $order->branch->name ?? 'غير محدد' }}</p>
                <p><strong>طريقة الدفع:</strong> {{ $order->paymentType->name ?? 'غير محدد' }}</p>
                <p><strong>حالة الطلب:</strong> {{ $order->status }}</p>
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
                        $subtotal = $detail->quantity * $detail->price;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->product->name ?? 'غير محدد' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }}</td>
                        <td>{{ number_format($subtotal, 2) }}</td>
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
        
        <div class="signature-section">
            <div class="signature-box">
                <p>المدير</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>أمين المخزن</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>المستلم</p>
                <div class="signature-line"></div>
            </div>
        </div>
        
        <div class="print-btn">
            <button onclick="window.print()">طباعة أمر الصرف</button>
        </div>
    </div>
</body>
</html>
