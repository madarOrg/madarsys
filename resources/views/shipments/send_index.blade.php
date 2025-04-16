<x-layout dir="rtl">
    <section class="relative mt-1 flex items-center">
        <x-title :title="'إرسال الشحنات'"></x-title>

        <a href="{{ route('shipments.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            العودة إلى قائمة الشحنات
        </a>
    </section>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mt-4">
        <h2 class="text-xl font-semibold mb-4">الشحنات الجاهزة للإرسال</h2>

        @if($shipments->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">لا توجد شحنات جاهزة للإرسال حالياً.</span>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-400 text-white">
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الشحنة</th>
                            <th class="py-2 px-4 border-b">المنتج</th>
                            <th class="py-2 px-4 border-b">الكمية</th>
                            <th class="py-2 px-4 border-b">تاريخ الشحنة</th>
                            <th class="py-2 px-4 border-b">الحالة</th>
                            <th class="py-2 px-4 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->shipment_number }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->product->name }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->quantity }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->shipment_date }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $shipment->status }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded">
                                        إرسال
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
