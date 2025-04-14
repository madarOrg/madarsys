<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'تقارير مرتجعات العملاء'"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <x-button href="{{ route('returns-management.reports') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-chart-bar ml-1"></i> التقارير العامة
            </x-button>
            
            <x-button href="{{ route('returns-management.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى المرتجعات
            </x-button>
        </div>
    </section>

    <!-- فلتر البحث -->
    <div class="bg-white p-4 rounded-lg shadow-md mt-4">
        <form method="GET" action="{{ route('returns-management.customer-reports') }}" class="flex flex-wrap items-center space-x-4 space-x-reverse">
            <div class="flex flex-col mb-2">
                <label for="customer_id" class="text-sm text-gray-600 mb-1">العميل</label>
                <select id="customer_id" name="customer_id" class="border border-gray-300 rounded-md p-2 w-60">
                    <option value="">-- اختر العميل --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customerId == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex flex-col mb-2">
                <label for="start_date" class="text-sm text-gray-600 mb-1">من تاريخ</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded-md p-2">
            </div>
            
            <div class="flex flex-col mb-2">
                <label for="end_date" class="text-sm text-gray-600 mb-1">إلى تاريخ</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded-md p-2">
            </div>
            
            <div class="flex items-end mb-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-filter ml-1"></i> تصفية
                </button>
            </div>
        </form>
    </div>

    @if($customerStats)
    <!-- إحصائيات العميل المحدد -->
    <div class="bg-white p-4 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">إحصائيات العميل: {{ $customers->where('id', $customerId)->first()->name ?? 'غير محدد' }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">إجمالي المرتجعات</h4>
                <p class="text-3xl font-bold text-blue-600">{{ $customerStats['total_returns'] }}</p>
            </div>
            
            <div class="bg-gray-100 p-4 rounded-lg">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">إجمالي العناصر المرتجعة</h4>
                <p class="text-3xl font-bold text-green-600">{{ $customerStats['total_items'] }}</p>
            </div>
            
            <div class="bg-gray-100 p-4 rounded-lg">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">متوسط العناصر لكل مرتجع</h4>
                <p class="text-3xl font-bold text-purple-600">
                    {{ $customerStats['total_returns'] > 0 ? number_format($customerStats['total_items'] / $customerStats['total_returns'], 1) : 0 }}
                </p>
            </div>
        </div>
        
        <!-- المنتجات الأكثر إرجاعاً للعميل المحدد -->
        <h4 class="text-lg font-semibold text-gray-700 mb-2">المنتجات الأكثر إرجاعاً</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">المنتج</th>
                        <th class="px-4 py-2">الكمية المرتجعة</th>
                        <th class="px-4 py-2">النسبة المئوية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerStats['top_products'] as $product)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $product->product->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ $product->total_quantity }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $percentage = $customerStats['total_items'] > 0 ? ($product->total_quantity / $customerStats['total_items']) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <span class="ml-2">{{ number_format($percentage, 1) }}%</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center">لا توجد بيانات متاحة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- جدول المرتجعات -->
    <div class="bg-white p-4 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            @if($customerId)
                مرتجعات العميل: {{ $customers->where('id', $customerId)->first()->name ?? 'غير محدد' }}
            @else
                جميع مرتجعات العملاء
            @endif
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">رقم المرتجع</th>
                        <th class="px-4 py-2">العميل</th>
                        <th class="px-4 py-2">تاريخ المرتجع</th>
                        <th class="px-4 py-2">سبب الإرجاع</th>
                        <th class="px-4 py-2">الحالة</th>
                        <th class="px-4 py-2">عدد العناصر</th>
                        <th class="px-4 py-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $return->return_number }}</td>
                            <td class="px-4 py-3">{{ $return->customer->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ Str::limit($return->return_reason, 30) }}</td>
                            <td class="px-4 py-3">
                                @if($return->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                                @elseif($return->status == 'completed')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                                @elseif($return->status == 'cancelled')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">ملغي</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $return->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $return->items->count() }}</td>
                            <td class="px-4 py-3">
                                <x-button href="{{ route('returns-management.show', $return->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md">
                                    <i class="fas fa-eye"></i>
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center">لا توجد بيانات متاحة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <x-pagination-links :paginator="$returns" />
        </div>
    </div>
</x-layout>
