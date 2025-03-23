<x-layout>

    <div class="container">
        <h1 class="mb-4">تقرير المنتجات المنتهية الصلاحية</h1>

        {{-- نموذج الفلترة --}}
        <form method="GET" action="{{ route('reports.expired-products') }}" class="mb-4">
            <div class="row g-3">
             
<!-- اختيار المستودع -->
<div class="col-md-3">
    <label for="warehouse_id" class="form-label">المستودع:</label>
    <select name="warehouse_id" id="warehouse_id" class="form-select">
        <option value="">كل المستودعات</option>
        @foreach ($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                {{ $warehouse->name }}
            </option>
        @endforeach
    </select>
</div>

                <!-- اختيار المنتج -->
                <div class="col-md-3">
                    <label for="product_id" class="form-label">المنتج:</label>
                    <select name="product_id" id="product_id" class="form-select">
                        <option value="">كل المنتجات</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- من تاريخ -->
                <div class="col-md-3">
                    <label for="expiration_from" class="form-label">من تاريخ:</label>
                    <input type="date" name="expiration_from" id="expiration_from" class="form-control" value="{{ request('expiration_from') }}">
                </div>

                <!-- إلى تاريخ -->
                <div class="col-md-3">
                    <label for="expiration_to" class="form-label">إلى تاريخ:</label>
                    <input type="date" name="expiration_to" id="expiration_to" class="form-control" value="{{ request('expiration_to') }}">
                </div>

                <!-- زر الفلترة -->
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                </div>
            </div>
        </form>

        {{-- عرض النتائج --}}
        @if ($report->isEmpty())
            <p class="alert alert-warning">لا توجد منتجات منتهية الصلاحية بناءً على الفلترة.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>رقم المنتج (SKU)</th>
                        <th>تاريخ الإنتاج</th>
                        <th>تاريخ انتهاء الصلاحية</th>
                        <th>الكمية المتاحة</th>
                        <th>المستودع</th>
                        <th>الإجراءات المتخذة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'غير متاح' }}</td>
                            <td>{{ $item->product->sku ?? 'غير متاح' }}</td>
                            <td>{{ $item->production_date ? \Carbon\Carbon::parse($item->production_date)->format('Y-m-d') : 'غير متاح' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->expiration_date)->format('Y-m-d') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->warehouseLocation->name ?? 'غير متاح' }}</td>
                            <td>
                                <button class="btn btn-warning">تم التخلص منها</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</x-layout>
