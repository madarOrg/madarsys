@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 rtl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">شحن الشحنة</h1>

        <div class="mb-6 bg-gray-100 p-4 rounded">
            <h2 class="text-xl font-semibold mb-2">معلومات الشحنة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><span class="font-bold">رقم الشحنة:</span> {{ $shipment->shipment_number }}</p>
                    <p><span class="font-bold">تاريخ الشحنة:</span> {{ $shipment->shipment_date }}</p>
                    <p><span class="font-bold">الحالة:</span> {{ $shipment->status }}</p>
                </div>
                <div>
                    <p><span class="font-bold">المنتج:</span> {{ $shipment->product->name }}</p>
                    <p><span class="font-bold">الكمية:</span> {{ $shipment->quantity }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('shipments.send', $shipment->id) }}" method="POST" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="received_quantity" class="block text-sm font-medium text-gray-700">الكمية المستلمة</label>
                <input type="number" name="received_quantity" id="received_quantity" value="{{ $shipment->quantity }}" min="1" max="{{ $shipment->quantity }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                @error('received_quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="received_date" class="block text-sm font-medium text-gray-700">تاريخ الاستلام</label>
                <input type="date" name="received_date" id="received_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                @error('received_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    شحن الشحنة
                </button>
                <a href="{{ route('shipments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
