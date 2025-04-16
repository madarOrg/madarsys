<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center justify-between">
        <x-title :title="'تفاصيل مرتجع المورد: ' . $returnOrder->return_number"></x-title>

        <div class="flex items-center space-x-2 space-x-reverse">
            <x-button href="{{ route('returns-suppliers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى مرتجعات الموردين
            </x-button>
            
            <x-button href="{{ route('returns-suppliers.print', $returnOrder->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md" target="_blank">
                <i class="fas fa-print ml-1"></i> طباعة المرتجع
            </x-button>
            
            @if($returnOrder->status == 'pending')
                <x-button href="{{ route('returns-suppliers.edit', $returnOrder->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-edit ml-1"></i> تعديل المرتجع
                </x-button>
                
                <form action="{{ route('returns-suppliers.send', $returnOrder->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من إرسال هذا المرتجع للمورد؟');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-paper-plane ml-1"></i> إرسال للمورد
                    </button>
                </form>
            @endif
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
                    <p class="text-gray-600 mb-1">تاريخ الإنشاء:</p>
                    <p class="font-semibold">{{ $returnOrder->created_at->format('Y-m-d') }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">الحالة:</p>
                    <p>
                        @if($returnOrder->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">معلق</span>
                        @elseif($returnOrder->status == 'sent')
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">تم الإرسال</span>
                        @elseif($returnOrder->status == 'completed')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                        @elseif($returnOrder->status == 'cancelled')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">ملغي</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $returnOrder->status }}</span>
                        @endif
                    </p>
                </div>
                
                @if($returnOrder->sent_at)
                <div>
                    <p class="text-gray-600 mb-1">تاريخ الإرسال:</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($returnOrder->sent_at)->format('Y-m-d') }}</p>
                </div>
                @endif
                
                <div class="col-span-2">
                    <p class="text-gray-600 mb-1">سبب الإرجاع:</p>
                    <p class="font-semibold">{{ $returnOrder->return_reason }}</p>
                </div>
            </div>
        </div>
        
        <!-- بيانات المورد -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">بيانات المورد</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 mb-1">اسم المورد:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->name ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">رقم الهاتف:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->phone ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">البريد الإلكتروني:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->email ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 mb-1">العنوان:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->address ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- عناصر المرتجع -->
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">عناصر المرتجع</h3>
        
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
                    @forelse($returnOrder->items as $index => $item)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $item->product->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3">{{ $item->return_reason ?? $returnOrder->return_reason }}</td>
                            <td class="px-4 py-3">
                                @if($item->is_sent)
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
                        $itemIds = $returnOrder->items->pluck('id')->toArray();
                        $transactions = \App\Models\InventoryTransaction::where(function($query) use ($itemIds) {
                            foreach($itemIds as $id) {
                                $query->orWhere('reference', 'RETURN-SUPPLIER-' . $id)
                                      ->orWhere('notes', 'like', '%مرتجع المورد%' . $id . '%');
                            }
                        })->get();
                    @endphp
                    
                    @forelse($transactions as $transaction)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $transaction->product->name ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3">{{ abs($transaction->quantity) }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">خصم من المخزون</span>
                            </td>
                            <td class="px-4 py-3">{{ $transaction->notes }}</td>
                            <td class="px-4 py-3">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
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
