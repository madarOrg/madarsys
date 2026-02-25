<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إدارة الشحنات'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('shipments.index') }}">
            <x-search-input id="search-shipments" name="search" placeholder="ابحث عن الشحنات" :value="request()->input('search')" />
        </form>
        <!-- زر إضافة شحنة جديدة -->
        <x-button :href="route('shipments.create')" type="button" class="mb-4">
            <i class="fas fa-plus mr-2"></i> إضافة شحنة جديدة
        </x-button>
    </section>

    <!-- جدول عرض الشحنات -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="w-full text-sm text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="p-4">رقم الشحنة</th>
                    <th class="px-6 py-3">الشركة\المنتج</th>
                    <th class="px-6 py-3">حالة الشحنة</th>
                    <th class="px-6 py-3">الكمية</th>
                    <th class="px-6 py-3">تاريخ الشحنة</th>

                    {{-- <th class="px-6 py-3">تاريخ تسليم الشحنة</th> --}}
                    {{-- <th class="px-6 py-3">شركة الشحنة</th>
                    <th class="px-6 py-3">عنوان الشحنة</th> --}}
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shipments as $shipment)
                    <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        <td class="p-4">{{ $shipment->shipment_number }}</td>
                        <td class="px-6 py-4">{{ $shipment->product->name }}-{{ $shipment->product->barcode }}-{{ $shipment->product->sku }}</td>
                        <td class="px-6 py-4">{{ $shipment->status }}</td>
                        <td class="px-6 py-4">{{ $shipment->quantity }}</td>

                        <td class="px-6 py-4">{{ $shipment->shipment_date }}</td>
                        {{-- <td class="px-6 py-4">{{ $shipment->delivery_date }}</td> <!-- إضافة تاريخ التسليم --> --}}
                        {{-- <td class="px-6 py-4">{{ $shipment->company_name }}</td> <!-- إذا كانت هناك شركة --> --}}
                        {{-- <td class="px-6 py-4">{{ $shipment->address }}</td> --}}
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- زر استلام الشحنة -->
                           <!-- رابط الاستلام -->
@if ($shipment->status != 'received')
<a href="{{ route('shipments.receive.form', $shipment->id) }}" class="text-green-600 hover:underline ml-4 text-lg">
    <i class="fas fa-check-circle"></i>
</a>
@endif

<!-- رابط التعديل -->
<a href="{{ route('shipments.edit', $shipment->id) }}" class="text-blue-600 hover:underline mx-4 text-lg">
<i class="fas fa-pen"></i>
</a>

<!-- رابط الحذف -->
<form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" class="inline-block">
@csrf
@method('DELETE')
<a href="#" onclick="event.preventDefault(); if(confirm('هل أنت متأكد من حذف هذه الشحنة؟')) this.closest('form').submit();" class="text-red-600 hover:text-red-800 mx-4 text-lg">
    <i class="fas fa-trash-alt"></i>
</a>
</form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
