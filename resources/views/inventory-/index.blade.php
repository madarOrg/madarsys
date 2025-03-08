<x-layout>
    <section>
        <div class="relative mt-2 flex items-center">
            <x-title :title="'المراجعة والتدقيق '"></x-title>
            <form method="GET" action="{{ route('inventory-review.index') }}" class="mb-4 flex items-center space-x-4">
                @csrf
            </form>
        </div>

        <!-- قائمة الحركات المعلقة -->
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 mt-4" id="pending-transactions-table">
            <thead class="text-xs text-gray-700 bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">رقم الحركة</th>
                    <th class="px-6 py-3">نوع العملية</th>
                    <th class="px-6 py-3">تاريخ العملية</th>
                    <th class="px-6 py-3">القسم</th>
                    <th class="px-6 py-3">المستودع</th>
                    <th class="px-6 py-3">الحالة</th>
                    <th class="px-6 py-3">الإجراءات</th>
                    <th class="px-6 py-3">ملاحظات</th>

                </tr>
            </thead>
            <tbody>
                <!-- عرض الحركات المعلقة -->
                @foreach ($reviews as $review)
                <tr>
                    <td class="px-6 py-3">{{ $review['transaction_number'] }}</td>
                    <td class="px-6 py-3">نوع العملية</td>
                    <td class="px-6 py-3">تاريخ العملية</td>
                    <td class="px-6 py-3">القسم</td>
                    <td class="px-6 py-3">المستودع</td>
                    <td class="px-6 py-3">
                        <span class="status-text">
                            @if ($review['status'] == 0)
                                معلق
                            @elseif ($review['status'] == 1)
                                موافق
                            @elseif ($review['status'] == 2)
                                مرفوض
                            @elseif ($review['status'] == 3)
                                تم إرسالها للمسؤول
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <select class="status-select bg-gray-200 text-gray-700 px-4 py-2 rounded" data-id="{{ $review['id'] }}">
                            <option value="0" {{ $review['status'] == 0 ? 'selected' : '' }}>معلق</option>
                            <option value="1" {{ $review['status'] == 1 ? 'selected' : '' }}>موافق</option>
                            <option value="2" {{ $review['status'] == 2 ? 'selected' : '' }}>مرفوض</option>
                            <option value="3" {{ $review['status'] == 3 ? 'selected' : '' }}>إرسال للمسؤول</option>
                        </select>
                        <button type="button" class="update-status-btn bg-green-500 text-white px-4 py-2 rounded" data-id="{{ $review['id'] }}">تحديث الحالة</button>
                        <td class="px-6 py-3">
                            
                        </td>

                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</x-layout>

<script>
    // التعامل مع الضغط على زر تحديث الحالة
    document.querySelectorAll('.update-status-btn').forEach(button => {
        button.addEventListener('click', function () {
            const reviewId = this.getAttribute('data-id');
            const statusSelect = this.closest('tr').querySelector('.status-select');
            const selectedStatus = statusSelect.value;
            const statusText = this.closest('tr').querySelector('.status-text');

            // إرسال طلب لتحديث الحالة
            fetch(`/inventory-review/${reviewId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: selectedStatus // إرسال القيمة الرقمية للحالة
                })
            })
            .then(response => response.json())
            .then(data => {
                // تحديث النص بناءً على الحالة الجديدة
                if (data.status == 0) {
                    statusText.textContent = 'معلق';
                } else if (data.status == 1) {
                    statusText.textContent = 'موافق';
                } else if (data.status == 2) {
                    statusText.textContent = 'مرفوض';
                } else if (data.status == 3) {
                    statusText.textContent = 'تم إرسالها للمسؤول';
                }
            })
            .catch(error => {
                console.error('حدث خطأ:', error);
            });
        });
    });
</script>
