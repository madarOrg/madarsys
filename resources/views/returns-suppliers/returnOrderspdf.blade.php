<!-- resources/views/returns-suppliers/returnOrders/pdf.blade.php -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب إرجاع المورد #{{ $returnOrder->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            font-size: 14px;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            text-align: right;
            border: 1px solid #ddd;
        }
        h2, h3 {
            color: #333;
        }
    </style>
</head>
<body>
    <h2>تفاصيل طلب الإرجاع #{{ $returnOrder->id }}</h2>

    <p><strong>اسم المورد:</strong> {{ $returnOrder->supplier->name }}</p>
    <p><strong>حالة الطلب:</strong> {{ $returnOrder->status }}</p>
    <p><strong>سبب الإرجاع:</strong> {{ $returnOrder->return_reason }}</p>
    <p><strong>تاريخ الإرجاع:</strong> {{ \Carbon\Carbon::parse($returnOrder->return_date)->format('Y-m-d') }}</p>

    <h3>تفاصيل المنتجات:</h3>
    <table>
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>الكمية</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returnOrder->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
