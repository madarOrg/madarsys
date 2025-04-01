
<div class="mb-6">
    <x-title :title="' بيانات الشركات'"></x-title>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">اسم الشركة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">الشعار</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">رقم الهاتف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">البريد الإلكتروني</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">العنوان</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">معلومات إضافية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">الإعدادات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">تم الإنشاء بواسطة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">  تاريخ الإنشاء</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 ">آخر تحديث بواسطة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 "> تاريخ التحدبث</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($this->selectedReport->report_data as $company)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if (!empty($company['logo']))
                                <img src="{{ asset('storage/' . $company['logo']) }}" alt="Logo" class="h-10 w-10 rounded-full">
                            @else
                                لا يوجد شعار
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['phone_number'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['email'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['address'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['additional_info'] ?? 'لا يوجد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ json_encode($company['settings'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['created_user'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['created_at'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['updated_user'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company['updated_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
