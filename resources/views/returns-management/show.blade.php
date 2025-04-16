<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'تفاصيل المرتجع: ' . $returnOrder->return_number"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <x-button href="{{ route('returns-management.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى المرتجعات
            </x-button>
            
            <x-button href="{{ route('returns-management.edit', $returnOrder->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-edit ml-1"></i> تعديل المرتجع
            </x-button>
        </div>
    </section>

    <!-- معلومات المرتجع -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- بيانات المرتجع -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">بيانات المرتجع</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 mb-1">رقم المرتجع:</p>
                    <p class="font-semibold">{{ $returnOrder->return_number }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">تاريخ المرتجع:</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($returnOrder->return_date)->format('Y-m-d') }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">الحالة:</p>
                    <p>
                        @if($returnOrder->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                        @elseif($returnOrder->status == 'completed')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                        @elseif($returnOrder->status == 'cancelled')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">ملغي</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $returnOrder->status }}</span>
                        @endif
                    </p>
                </div>
                
                <div class="col-span-2">
                    <p class="text-gray-600 mb-1">سبب الإرجاع:</p>
                    <p class="font-semibold">{{ $returnOrder->return_reason }}</p>
                </div>
            </div>
        </div>
        
        <!-- بيانات العميل -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">بيانات العميل</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 mb-1">اسم العميل:</p>
                    <p class="font-semibold">{{ $returnOrder->customer->name ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">رقم الهاتف:</p>
                    <p class="font-semibold">{{ $returnOrder->customer->phone ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">البريد الإلكتروني:</p>
                    <p class="font-semibold">{{ $returnOrder->customer->email ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">العنوان:</p>
                    <p class="font-semibold">{{ $returnOrder->customer->address ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- عناصر المرتجع -->
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-800 border-b pb-2">عناصر المرتجع</h3>
            
            <!-- مربع البحث -->
            <form method="GET" action="{{ route('returns-management.show', $returnOrder->id) }}" class="w-1/3">
                <x-search-input id="search-return-items" name="search" placeholder="ابحث عن المنتجات المرتجعة" :value="request()->input('search')" />
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">المنتج</th>
                        <th class="px-4 py-2">الكمية</th>
                        <th class="px-4 py-2">سبب الإرجاع</th>
                        <th class="px-4 py-2">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $item->product->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3">{{ $item->return_reason ?? $returnOrder->return_reason }}</td>
                            <td class="px-4 py-3">
                                @if($item->Is_Send)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">تم الإرسال</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center">لا توجد عناصر في هذا المرتجع</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <x-pagination-links :paginator="$items" />
        </div>
    </div>

    <!-- سجل الحركات -->
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">سجل حركة المخزون</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">المنتج</th>
                        <th class="px-4 py-2">الكمية</th>
                        <th class="px-4 py-2">نوع الحركة</th>
                        <th class="px-4 py-2">ملاحظات</th>
                        <th class="px-4 py-2">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // البحث عن عناصر المعاملات المخزنية المرتبطة بعناصر المرتجعات
                        $transactionItems = \App\Models\InventoryTransactionItem::whereIn('reference_item_id', $items->pluck('id'))
                            ->with(['inventoryTransaction', 'product'])
                            ->get();
                    @endphp
                    
                    @forelse($transactionItems as $item)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $item->product->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ abs($item->quantity) }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">إضافة للمخزون</span>
                            </td>
                            <td class="px-4 py-3">{{ $item->inventoryTransaction->notes ?? '' }}</td>
                            <td class="px-4 py-3">{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center">لا توجد حركات مخزون مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>