<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>طباعة حركة السحب</title>
    <style>
        body { font-family: 'Arial'; direction: rtl; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .print-button {
            display: block;
            width: 200px;
            margin: 0 auto 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

<button class="print-button" onclick="window.print()">🖨️ طباعة الحركة</button>

<div class="header">
    <h2>حركة سحب من المخزون</h2>
    <p>رقم الحركة: {{ $transaction->id }}</p>
    <p>التاريخ: {{ $transaction->approved_at }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>المنتج</th>
            <th>رقم الدفعة</th>
            <th>تاريخ الإنتاج</th>
            <th>تاريخ الانتهاء</th>
            <th>الكمية المسحوبة</th>
            <th>الوحدة</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaction->items as $item)
            @foreach ($item->inventoryProducts as $withdrawal)
                <tr>
                    <td>{{ $item->product->name ?? 'غير معروف' }}</td>
                    <td>{{ $withdrawal->batch_number }}</td>
                    <td>{{ $withdrawal->production_date }}</td>
                    <td>{{ $withdrawal->expiration_date }}</td>
                    <td>{{ abs($withdrawal->quantity) }}</td>
                    <td>{{ $withdrawal->unit->name ?? '' }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

</body>
</html>
