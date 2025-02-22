<x-layout dir="rtl">
    <div class="relative mt-1 flex items-center">
        <x-title :title="'إدارة الشركاء'"></x-title>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('partners.index') }}">
            <x-search-input id="search-partners" name="search" placeholder="ابحث عن الشركاء" :value="request()->input('search')" />
        </form>
    </div>

    <!-- زر إضافة شريك جديد -->
    <x-button :href="route('partners.create')" type="button">
        <i class="fas fa-plus mr-2"></i> إضافة شريك جديد
    </x-button>

    <!-- جدول عرض الشركاء -->
    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="p-4">
                    <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                </th>
                <th class="px-6 py-3">اسم الشريك</th>
                <th class="px-6 py-3">نوع الشريك</th>
                <th class="px-6 py-3">البريد الإلكتروني</th>
                <th class="px-6 py-3">رقم الهاتف</th>
                <th class="px-6 py-3">العنوان</th>
                <th class="px-6 py-3">رقم الضريبة</th>
                <th class="px-6 py-3">الحالة</th>
                <th class="px-6 py-3">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($partners as $partner)
                <tr
                    class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <input type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                        <a href="javascript:void(0)"
                            onclick="togglePartnerDetails({{ $partner->id }})">{{ $partner->name }}</a>
                    </td>
                    <td class="px-6 py-4">{{ $partner->partnerType->name ?? 'غير محدد' }}</td>
                    <!-- عرض اسم النوع (الشريك) -->
                    <td class="px-6 py-4">{{ $partner->email ?? 'غير متوفر' }}</td>
                    <td class="px-6 py-4">{{ $partner->phone ?? 'غير متوفر' }}</td>
                    <td class="px-6 py-4">{{ $partner->address ?? 'غير متوفر' }}</td> <!-- إضافة العنوان -->
                    <td class="px-6 py-4">{{ $partner->tax_number ?? 'غير متوفر' }}</td> <!-- إضافة رقم الضريبة -->
                    <td class="px-6 py-4">
                        <span
                            class="px-2 py-1 rounded text-white text-xs {{ $partner->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $partner->is_active ? 'فعال' : 'غير فعال' }}
                        </span>

                    </td>

                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('partners.edit', $partner->id) }}"
                            class="text-blue-600 hover:underline dark:text-blue-500">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('partners.destroy', $partner->id) }}" method="POST"
                            class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- تفاصيل الشريك -->
                <tr id="partner-details-{{ $partner->id }}" class="hidden">
                    <td colspan="6" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <x-title :title="'تفاصيل الشريك: ' . $partner->name" />

                        <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-600 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">العنوان</th>
                                    <th class="px-6 py-3">الموقع الإلكتروني</th>
                                    <th class="px-6 py-3">ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $partner->address ?? 'غير متوفر' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($partner->website)
                                            <a href="{{ $partner->website }}" target="_blank"
                                                class="text-blue-500 hover:underline">{{ $partner->website }}</a>
                                        @else
                                            غير متوفر
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $partner->notes ?? 'لا توجد ملاحظات' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-pagination-links :paginator="$partners" />

    <script>
        function togglePartnerDetails(partnerId) {
            let detailsRow = document.getElementById(`partner-details-${partnerId}`);
            detailsRow.classList.toggle("hidden");
        }
    </script>
</x-layout>
