@foreach($transactions as $transaction)
    <h2 style="margin-top: 30px; font-size: 24px; color: #4B5563;">مستودع: {{ $transaction->warehouse->name ?? '-' }}</h2>

    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead style="background-color: #f3f4f6;">
            <tr>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">اسم المنتج</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">الكمية المتوقعة</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">الكمية الفعلية</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">الفرق</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                        {{ $item->product->name ?? '-' }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                        {{ $item->expected_audit_quantity }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                        {{ $item->quantity }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; 
                        color: {{ ($item->expected_audit_quantity - $item->quantity) == 0 ? '#10B981' : '#EF4444' }};
                    ">
                        {{ $item->expected_audit_quantity - $item->quantity }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
