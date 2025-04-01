<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-lg font-semibold mb-4">بيانات الفروع</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الفرع</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">معلومات الاتصال</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">معرف الشركة</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إنشاء بواسطة</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تحديث بواسطة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($this->selectedReport->data as $branch)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $branch['name'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $branch['address'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $branch['contact_info'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $branch['company_id'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $branch['created_user'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $branch['updated_user'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
