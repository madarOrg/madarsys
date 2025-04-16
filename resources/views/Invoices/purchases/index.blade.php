<x-layout dir="rtl">
    <section class=" p-6  shadow-md rounded-lg">
        <div class="  ">
            <div class=" flex items-center">
                <x-title :title="'فواتير المشتريات'"></x-title>
                <div class=" flex "> <!-- Align the button to the left -->
                    <x-button :href="route('invoices.create', ['type' => 'purchase'])" type="button">
                        <i class="fas fa-plus mr-2"></i> إضافة فاتورة جديدة
                    </x-button>
                </div>
            </div>
            <!-- Search Form with Filters -->
            <form method="GET" action="{{ route('invoices.index', ['type' => 'purchase']) }}">
                <div x-data="{ open: true }">
                    <!-- زر لفتح أو إغلاق القسم -->
                    <button type="button" @click="open = !open"
                        class="text-indigo-600 hover:text-indigo-700 mt-4 mb-2 ml-4">
                        <span
                            x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                        </span>
                    </button>
                    <!-- الحقول القابلة للطي -->
                    <div x-show="open" x-transition>
                        <div class="flex flex-wrap justify-between gap-4">
                            <div class="flex-1 min-w-[150px] mt-2">
                                <label for="warehouse_id"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                                <select name="warehouse_id" id="warehouse_id" class="tom-select ">
                                    <option value="">اختر المستودع</option>
                                    @foreach ($Warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[150px] mt-2">
                                <label for="partner_id"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">العميل</label>
                                <select name="partner_id" id="partner_id" class="tom-select">
                                    <option value="">اختر العميل</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}"
                                            {{ request()->input('partner_id') == $partner->id ? 'selected' : '' }}>
                                            {{ $partner->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <x-file-input id="invoice_Code" name="invoice_code"
                                    value="{{ request('invoice_code') ?? '' }}" label="رقم الفاتورة" />
                            </div>
                            <div>
                                <!-- Filter by Branch -->
                                {{-- <div class="col-span-1">
                                <label for="branch_id" class="text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                                <select name="branch_id" id="branch_id" class="w-full bg-gray-100 rounded border py-1 px-3 leading-8">
                                    <option value="">اختر الفرع</option>
                                    @foreach ($branches as $branche)
                                        <option value="{{ $branche->id }}" {{ request()->input('branch_id') == $branche->id ? 'selected' : '' }}>
                                            {{ $branche->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}


                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <x-file-input id="start_date" name="start_date" label="من" type="date"
                                    :value="request()->input('start_date') ?? now()->toDateString()" />

                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <x-file-input id="end_date" name="end_date" label="إلى" type="date"
                                    :value="request()->input('end_date') ?? now()->toDateString()" />

                            </div>

                            <div class="flex-1 min-w-[150px] ">
                                <label for="payment_type_id"
                                    class="block text-sm font-medium text-gray-600 dark:text-gray-400">
                                    طريقة الدفع
                                </label>

                                <select name="payment_type_id" id="payment_type_id"
                                    class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                                                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                                                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                                    <option value="">اختر طريقة الدفع</option>
                                    @foreach ($paymentTypes->pluck('name', 'id') as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ (string) request('payment_type_id') === (string) $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            

                        </div>
                        <div class="flex justify-end">
                            <x-button type="submit" class="">بحث</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Invoices Table -->
        <div class="overflow-x-auto  shadow-md rounded-lg ">
            <table id="invoice-items-table" class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4">
                <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="px-6 py-3">المستودع</th>

                        <th class="px-6 py-3">كود الفاتورة</th>
                        <th class="px-6 py-3">اسم العميل</th>
                        <th class="px-6 py-3">تاريخ الفاتورة</th>
                        <th class="px-6 py-3">المبلغ الإجمالي</th>
                        <th class="px-6 py-3">الخصم</th>
                        <th class="px-6 py-3">طريقة الدفع</th>
                        <th class="px-6 py-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr
                            class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <td class="p-4">{{ $invoice->id }}</td>
                            <td class="px-6 py-4">{{ optional($invoice->warehouse)->name ?? 'غير محدد' }}</td>

                            <td class="px-6 py-4">{{ $invoice->invoice_code }}</td>
                            <td class="px-6 py-4">{{ optional($invoice->partner)->name ?? 'غير محدد' }}</td>
                            <td class="px-6 py-4">{{ $invoice->invoice_date }}</td>
                            <td class="px-6 py-4">{{ $invoice->total_amount }}</td>
                            <td class="px-6 py-4">{{ $invoice->discount_amount }}</td>

                            <td class="px-6 py-4">{{ optional($invoice->paymentType)->name ?? 'غير محدد' }}</td>
                            <td class="px-6 py-4 flex">
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">

                                    <a href="{{ route('invoices.edit', ['type' => 'purchase', 'invoice' => $invoice->id]) }}"
                                        class="text-blue-600 hover:underline dark:text-blue-500">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form
                                        action="{{ route('invoices.destroy', ['type' => 'purchase', 'invoice' => $invoice->id]) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('هل أنت متأكد من حذف الفاتورة؟')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <x-pagination-links :paginator="$invoices" />
    </section>
</x-layout>
