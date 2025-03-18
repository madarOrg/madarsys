<div>
    <!-- فلترة الحركات -->
    <div class="filters mb-4">
        <input wire:model="reference" type="text" placeholder="الرقم المرجعي">
        <select wire:model="warehouse_id">
            <option value="">اختر المستودع</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>

        <select wire:model="status">
            <option value="">الحالة</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>

        <select wire:model="transaction_type">
            <option value="">النوع</option>
            @foreach($transactionTypes as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>

        <input wire:model="product_name" type="text" placeholder="اسم المنتج">
        <input wire:model="quantity_from" type="number" placeholder="الكمية من">
        <input wire:model="quantity_to" type="number" placeholder="الكمية إلى">

        <input wire:model="created_at_from" type="date">
        <input wire:model="created_at_to" type="date">
    </div>

    <!-- عرض الحركات -->
    <table class="table">
        <thead>
            <tr>
                <th>رقم الحركة</th>
                <th>المستودع</th>
                <th>المنتج</th>
                <th>الكمية</th>
                <th>الحالة</th>
                <th>النوع</th>
                <th>تاريخ الإدخال</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->reference }}</td>
                    <td>{{ $transaction->warehouse_name }}</td>
                    <td>{{ $transaction->product_name }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>{{ $transaction->transaction_type_id }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">لا توجد نتائج</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $transactions->links() }}
</div>
