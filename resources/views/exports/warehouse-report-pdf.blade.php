<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المستودع</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 text-gray-800">
    <div class="bg-white p-6 rounded-lg shadow-md text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-700">تقرير المستودع</h1>
        <p class="text-sm text-gray-500 mt-2">تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
    </div>

    @php
       $fieldLabels = [
    'name' => 'اسم المستودع',
    'address' => 'العنوان',
    'contact_info' => 'معلومات الاتصال',
    'branch_id' => 'معرف الفرع',
    'supervisor_id' => 'معرف المسؤول',
    'latitude' => 'خط العرض',
    'longitude' => 'خط الطول',
    'area' => 'المساحة',
    'shelves_count' => 'عدد الأرفف',
    'capacity' => 'السعة',
    'is_smart' => 'مستودع ذكي',
    'has_security_system' => 'نظام الأمان',
    'has_cctv' => 'وجود كاميرات المراقبة',
    'is_integrated_with_wms' => 'مربوط بنظام إدارة المستودعات',
    'last_maintenance' => 'آخر صيانة',
    'has_automated_systems' => 'نظام آلي',
    'temperature' => 'درجة الحرارة',
    'humidity' => 'الرطوبة',
    'code' => 'الكود',
    'is_active' => 'مفعل',
    'created_user' => 'مستخدم الإنشاء',
    'updated_user' => 'مستخدم التحديث',
];

    @endphp

    @if($report->report_type === 'details')
        @foreach($report->report_data['warehouse_info'] as $key => $value)
            <div>
                <span class="font-semibold text-gray-600">{{ $fieldLabels[$key] ?? 'غير معرف' }}:</span>
                <span class="text-gray-800">
                    @if(is_array($value))
                        {{ implode(', ', array_map(function($item) { return is_array($item) ? implode(', ', $item) : strval($item); }, $value)) }}
                    @else
                        {{ $value }}
                    @endif
                </span>
            </div>
        @endforeach
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">مميزات المستودع</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach($report->report_data['features'] as $key => $value)
                <div>
                    <span class="font-semibold text-gray-600">{{ $fieldLabels[$key] ?? 'غير معرف' }}:</span>
                    <span class="text-gray-800">
                        @if(is_array($value))
                            {{ implode(', ', array_map('strval', $value)) }}
                        @else
                            {{ $value ? 'نعم' : 'لا' }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    @if($report->report_type === 'inventory')
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">تفاصيل المخزون</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach($report->report_data['inventory'] as $key => $value)
                    <div>
                        <span class="font-semibold text-gray-600">{{ $fieldLabels[$key] ?? 'غير معرف' }}:</span>
                        <span class="text-gray-800">
                            @if(is_array($value))
                                {{ implode(', ', array_map('strval', $value)) }}
                            @else
                                {{ $value }}
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($report->report_type === 'movement')
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">تفاصيل الحركة</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach($report->report_data['movements'] as $key => $value)
                    <div>
                        <span class="font-semibold text-gray-600">{{ $fieldLabels[$key] ?? 'غير معرف' }}:</span>
                        <span class="text-gray-800">
                            @if(is_array($value))
                                {{ implode(', ', array_map('strval', $value)) }}
                            @else
                                {{ $value }}
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</body>
</html>
