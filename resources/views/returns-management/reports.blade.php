<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'تقارير المرتجعات'"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <x-button href="{{ route('returns-management.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى المرتجعات
            </x-button>
            
            {{-- زر الطباعة --}}
            <x-button onclick="window.print();" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md cursor-pointer">
                <i class="fas fa-print ml-1"></i> طباعة
            </x-button>
        </div>
    </section>

    <!-- فلتر التاريخ -->
    <div class="bg-white p-4 rounded-lg shadow-md mt-4">
        <form method="GET" action="{{ route('returns-management.reports') }}" class="flex items-center space-x-4 space-x-reverse">
            <div class="flex flex-col">
                <label for="start_date" class="text-sm text-gray-600 mb-1">من تاريخ</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded-md p-2">
            </div>
            
            <div class="flex flex-col">
                <label for="end_date" class="text-sm text-gray-600 mb-1">إلى تاريخ</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded-md p-2">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-filter ml-1"></i> تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- ملخص الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">إجمالي المرتجعات</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalReturns }}</p>
            <p class="text-sm text-gray-500 mt-2">خلال الفترة المحددة</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">إجمالي العناصر المرتجعة</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalReturnItems }}</p>
            <p class="text-sm text-gray-500 mt-2">عدد المنتجات المرتجعة</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">متوسط العناصر لكل مرتجع</h3>
            <p class="text-3xl font-bold text-purple-600">
                {{ $totalReturns > 0 ? number_format($totalReturnItems / $totalReturns, 1) : 0 }}
            </p>
            <p class="text-sm text-gray-500 mt-2">متوسط عدد المنتجات في كل مرتجع</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">عدد العملاء</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $customerReturns->count() }}</p>
            <p class="text-sm text-gray-500 mt-2">عدد العملاء الذين لديهم مرتجعات</p>
        </div>
    </div>

    <!-- المنتجات الأكثر إرجاعاً -->
    <div class="bg-white p-4 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">المنتجات الأكثر إرجاعاً</h3>
        
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
                    @forelse($topReturnedProducts as $product)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $product->product->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ $product->total_quantity }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $percentage = $totalReturnItems > 0 ? ($product->total_quantity / $totalReturnItems) * 100 : 0;
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

    <!-- أسباب الإرجاع الأكثر شيوعاً -->
    <div class="bg-white p-4 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">أسباب الإرجاع الأكثر شيوعاً</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">السبب</th>
                        <th class="px-4 py-2">عدد المرات</th>
                        <th class="px-4 py-2">النسبة المئوية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topReturnReasons as $reason)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $reason->return_reason ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ $reason->count }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $percentage = $totalReturns > 0 ? ($reason->count / $totalReturns) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <span class="ml-2">{{ number_format($percentage, 1) }}%</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
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

    <!-- توزيع المرتجعات حسب الحالة -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">توزيع المرتجعات حسب الحالة</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                        <tr>
                            <th class="px-4 py-2">الحالة</th>
                            <th class="px-4 py-2">العدد</th>
                            <th class="px-4 py-2">النسبة المئوية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returnsByStatus as $status)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-4 py-3">
                                    @if($status->status == 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                                    @elseif($status->status == 'completed')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                                    @elseif($status->status == 'cancelled')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">ملغي</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $status->status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $status->count }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $percentage = $totalReturns > 0 ? ($status->count / $totalReturns) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <span class="ml-2">{{ number_format($percentage, 1) }}%</span>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
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

        <!-- العملاء الأكثر إرجاعاً -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">العملاء الأكثر إرجاعاً</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                        <tr>
                            <th class="px-4 py-2">العميل</th>
                            <th class="px-4 py-2">عدد المرتجعات</th>
                            <th class="px-4 py-2">النسبة المئوية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customerReturns as $customer)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-4 py-3">{{ $customer->customer->name ?? 'غير محدد' }}</td>
                                <td class="px-4 py-3">{{ $customer->count }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $percentage = $totalReturns > 0 ? ($customer->count / $totalReturns) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <span class="ml-2">{{ number_format($percentage, 1) }}%</span>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-purple-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
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
    </div>
</x-layout>
