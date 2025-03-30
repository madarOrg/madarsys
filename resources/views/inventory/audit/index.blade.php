<x-layout>

    <div class="container">
        <h1 class="mb-4">جرد المخزون</h1>

        <form method="GET" action="{{ route('inventory.audit.index') }}" class="mb-4">
            <div class="row g-3">
                <!-- فلترة حسب حالة المخزون -->
                <div class="col-md-3">
                    <label class="form-label">حالة المخزون:</label>
                    <select name="stock_status" class="form-select">
                        <option value="">الكل</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>منخفض</option>
                        <option value="excess" {{ request('stock_status') == 'excess' ? 'selected' : '' }}>فائض</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>غير متوفر
                        </option>
                    </select>
                </div>

                      <!-- فلترة حسب نوع الجرد -->
                      <div class="col-md-3">
                        <label class="form-label">نوع الفلترة:</label>
                        <select name="filter" class="form-select">
                            <option value="">اختر نوع الفلترة</option>
                            <option value="recent" {{ request('filter') == 'recent' ? 'selected' : '' }}>
                                تم الجرد حديثاً
                            </option>
                            <option value="never" {{ request('filter') == 'never' ? 'selected' : '' }}>
                                لم يُجرَد أبداً
                            </option>
                            <option value="not_since" {{ request('filter') == 'not_since' ? 'selected' : '' }}>
                                لم يُجرَد منذ تاريخ
                            </option>
                        </select>
                    </div>
    
                    <!-- حقل التاريخ يظهر عند اختيار فلترة "not_since" -->
                    <div class="col-md-3">
                        <label class="form-label">من تاريخ:</label>
                        <input type="date" name="given_date" class="form-control" value="{{ request('given_date') }}">
                    </div>

                <!-- فلترة حسب التصنيف -->
                <div class="col-md-3">
                    <label class="form-label">التصنيف:</label>
                    <select name="category_id" id="category_id" class="tom-select">
                        <option value="">كل التصنيفات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <!-- البحث باسم المنتج أو SKU -->
                <div class="col-md-3">
                    <label class="form-label">البحث:</label>
                    <input type="text" name="search" class="form-control" placeholder="اسم المنتج أو SKU"
                        value="{{ request('search') }}">
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">بحث</button>
                <a href="{{ route('inventory.audit.index') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>SKU</th>
                    <th>الكمية المتوفرة</th>
                    <th>آخر عملية جرد</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->inventory->quantity ?? 0 }}</td>
                        <td>{{ $product->last_inventory_date ? $product->last_inventory_date->format('Y-m-d') : 'غير متوفر' }}
                        </td>
                        <td>{{ $product->status }}</td>
                        <td>{{ $product->inventoryTransactions->sum('quantity') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا توجد نتائج</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- {{ $products->links() }} --}}
    </div>

</x-layout>
