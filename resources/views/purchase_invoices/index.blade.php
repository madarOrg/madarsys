<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'قائمة فواتير المشتريات'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('purchase_invoices.index') }}">
            <x-search-input id="search-purchase-invoices" name="search" placeholder="ابحث عن فواتير المشتريات" :value="request()->input('search')" />
        </form>
    </section>

    <!-- زر إضافة فاتورة جديدة -->
    <x-button :href="route('purchase_invoices.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة فاتورة مشتريات جديدة
    </x-button>

    <!-- جدول عرض فواتير المشتريات -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">#</th>
                    <th class="px-6 py-3">اسم المورد</th>
                    <th class="px-6 py-3">تاريخ الفاتورة</th>
                    <th class="px-6 py-3">المبلغ الإجمالي</th>
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseInvoices as $invoice)
                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="p-4">{{ $invoice->id }}</td>
                        <td class="px-6 py-4">{{ $invoice->supplier->name }}</td>
                        <td class="px-6 py-4">{{ $invoice->invoice_date }}</td>
                        <td class="px-6 py-4">{{ $invoice->total_amount }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <x-button href="{{ route('purchase_invoices.edit', $invoice->id) }}" class="text-yellow-600 hover:underline">
                                <i class="fas fa-pen"></i>
                            </x-button>
                            <form action="{{ route('purchase_invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('هل أنت متأكد من حذف الفاتورة؟')">
                                    <i class="fas fa-trash-alt"></i>
                                </x-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination-links :paginator="$purchaseInvoices" />
</x-layout>
