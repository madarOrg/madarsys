<x-layout dir="rtl">
    <section class="relative mt-5 flex flex-col items-start">
        <div class="flex items-center justify-between mb-4">
            <x-title :title="'إدارة الطلبات'"></x-title>
        
            <form method="GET" action="{{ route('orders.index') }}" class="flex items-center space-x-2 rtl:space-x-reverse">
                <x-search-input
                    id="search-orders"
                    name="search"
                    placeholder="ابحث عن الطلبات"
                    :value="request()->input('search')"
                    class="w-1/4 p-2 border border-gray-300 rounded-md shadow-sm"
                />
            </form>
        </div>
         <div class="flex">
                <x-button :href="route('orders.pending-approval')" type="button" class=" ">
                    <i class="fas fa-clipboard-list mr-2"></i> الطلبات المعلقة
                </x-button>
                <x-button :href="route('orders.check-confirmed')" type="button" class="">
                    <i class="fas fa-clipboard-check mr-2"></i> الطلبات المؤكدة
                </x-button>
                <x-button :href="route('orders.create')" type="button" class="">
                    <i class="fas fa-plus mr-2"></i> إضافة طلب جديد
                </x-button>
            </div>
        </form>
        <!-- جدول عرض الطلبات -->
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                        <th class="">رقم الطلب</th>
                        {{-- <th class="px-6 py-3">الفرع</th> --}}
                        <th class="px-6 py-3">المستودع</th>
                        <th class="px-6 py-3">نوع الطلب</th>
                        <th class="px-6 py-3">حالة الطلب</th>
                        <th class="px-6 py-3">المنتج</th>
                        {{-- <th class="px-6 py-3">الكمية</th>
                        <th class="px-6 py-3">السعر</th> --}}
                        <th class="px-6 py-3">طريقة الدفع</th>
                        <th class="px-6 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr  class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">

                        <td class="p-2">{{ $order->id }}</td>
                            {{-- <td class="px-6 py-4">{{ $order->branch->name ?? 'لايوجد' }}</td> --}}
                            <td class="px-6 py-4">{{ $order->warehouse->name ?? 'لايوجد' }}</td>

                            <td class="px-6 py-4">{{ $order->type == 'buy' ? 'شراء' : 'بيع' }}</td>
                            <td class="px-6 py-4">{{ $order->status }}</td>
                            <td class="px-6 py-4">
                                @foreach($order->order_details as $detail)
                                    • {{ $detail->product->name ?? 'لا يوجد' }} - {{ $detail->product->barcode ?? 'لا يوجد' }} - {{ $detail->product->sku ?? 'لا يوجد' }}  
                                    ({{ $detail->quantity }}  
                                    {{ $detail->unit->name ?? 'بدون وحدة' }})<br>
                                @endforeach
                            </td>
                        
                            <td class="px-6 py-4">{{ $order->paymentType->name ?? 'لايوجد' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.edit', $order->id) }}"
                                    class="text-blue-600 hover:underline dark:text-blue-400">
                                     <i class="fa-solid fa-pen"></i>
                                 </a>
                                {{-- <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">حذف</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>  <x-pagination-links :paginator="$orders" />
          

        </div>

      
    </section>
</x-layout> 
