<x-layout dir="rtl">
    <section class=" p-3 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        <div class="space-y-12 dark:bg-gray-900 ">
            <div class="flex mb-4">
                <x-title :title="'فواتير المشتريات'"></x-title>
            
                <div class="flex space-x-2 rtl:space-x-reverse">
                    <x-button :href="route('invoices.create', ['type' => 'purchase'])" class="">
                        <i class="fas fa-plus mr-2"></i> إضافة فاتورة جديدة
                    </x-button>
                    <x-button :href="route('invoices.purchase-orders')" class="">
                        <i class="fas fa-clipboard-check mr-2"></i> فواتير من طلبات الشراء
                    </x-button>
                </div>
            </div>
            
            

            <!-- نموذج البحث -->
            <form method="GET" action="{{ route('invoices.index', ['type' => 'purchase']) }}">
                <div x-data="{ open: true }">
                    <button type="button" @click="open = !open"
                        class="text-indigo-600 hover:text-indigo-700  ml-4">
                        <span
                            x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                        </span>
                    </button>

                    <div x-show="open" x-transition>
                        <div class="flex flex-wrap justify-between gap-4">
                            <div class="flex-1 min-w-[150px] mt-2">
                                <label for="warehouse_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                                <select name="warehouse_id" id="warehouse_id" class="tom-select">
                                    <option value="">اختر المستودع</option>
                                    @foreach ($Warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[150px] mt-2">
                                <label for="partner_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">العميل</label>
                                <select name="partner_id" id="partner_id" class="tom-select">
                                    <option value="">اختر العميل</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
                                            {{ $partner->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[150px] mt-2">
                                <x-file-input id="invoice_Code" name="invoice_code"
                                    value="{{ request('invoice_code') ?? '' }}" label="رقم الفاتورة" />
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <x-file-input id="start_date" name="start_date" label="من" type="date"
                                    :value="request('start_date') ?? now()->toDateString()" />
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <x-file-input id="end_date" name="end_date" label="إلى" type="date"
                                    :value="request('end_date') ?? now()->toDateString()" />
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <label for="payment_type_id" class="block text-sm font-medium text-gray-600 dark:text-gray-400">
                                    طريقة الدفع
                                </label>
                                <select name="payment_type_id" id="payment_type_id"
                                    class="w-full bg-gray-100 rounded border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 py-1 px-3 mt-1">
                                    <option value="">اختر طريقة الدفع</option>
                                    @foreach ($paymentTypes as $id => $name)
                                        <option value="{{ $id }}" {{ (string) request('payment_type_id') === (string) $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <x-button type="submit">بحث</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- جدول الفواتير -->
        <div class="overflow-x-auto  shadow-md rounded-lg ">
            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="px-6 py-3">رقم الفاتورة</th>
                        <th class="px-6 py-3">الفرع</th>
                        <th class="px-6 py-3">المستودع</th>
                        <th class="px-6 py-3">اسم العميل</th>
                        <th class="px-6 py-3">تاريخ الفاتورة</th>
                        <th class="px-6 py-3">المبلغ الإجمالي</th>
                        <th class="px-6 py-3">الخصم</th>
                        <th class="px-6 py-3">طريقة الدفع</th>
                        <th class="px-6 py-3">رقم الطلب</th>
                        <th class="px-6 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr class=" border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <td class="p-4">{{ $invoice->id }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $invoice->invoice_code }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($invoice->branch)->name ?? 'غير محدد' }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($invoice->warehouse)->name ?? 'غير محدد' }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($invoice->partner)->name ?? 'غير محدد' }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $invoice->invoice_date }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $invoice->total_amount }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ $invoice->discount_amount }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">{{ optional($invoice->paymentType)->name ?? 'غير محدد' }}</td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap">
                                @if($invoice->order_id)
                                    @if($invoice->order && $invoice->order->type === 'purchase')
                                        <a href="{{ route('purchase-orders.show', $invoice->order_id) }}" class="text-blue-600 hover:underline">{{ $invoice->order_id }}</a>
                                    @elseif($invoice->order && $invoice->order->type === 'sale')
                                        <a href="{{ route('sales-orders.show', $invoice->order_id) }}" class="text-blue-600 hover:underline">{{ $invoice->order_id }}</a>
                                    @else
                                        {{ $invoice->order_id }}
                                    @endif
                                @else
                                    غير مرتبط بطلب
                                @endif
                            </td>
                            <td class=" p-2 w-auto min-w-[50px] whitespace-nowrap flex space-x-2">
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">

                                <a href="{{ route('invoices.edit', ['type' => 'purchase', 'invoice' => $invoice->id]) }}"
                                    class="text-blue-600 hover:underline dark:text-blue-400">
                                     <i class="fa-solid fa-pen"></i>
                                 </a>
                                 
                                 <form action="{{ route('invoices.destroy', ['type' => 'purchase', 'invoice' => $invoice->id]) }}" method="POST" class="inline-block">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('هل أنت متأكد من حذف الفاتورة؟')">
                                         <i class="fas fa-trash-alt"></i>
                                     </button>
                                 </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-gray-600 dark:text-gray-300">لا توجد فواتير</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <x-pagination-links :paginator="$invoices" />

        </div>
    </section>
</x-layout>
