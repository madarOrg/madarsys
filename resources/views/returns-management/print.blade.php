    <title>طباعة مرتجع العميل #{{ $returnOrder->return_number }}</title>
    <a href="{{ route('returns-management.show', $returnOrder->id) }}"
        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow-md mr-2">
        <i class="fas fa-arrow-right ml-1"></i> العودة
    </a>
    <x-base>
        <div class="print-container max-w-4xl mx-auto my-8 bg-white p-8 shadow-md border">

            <div class="hide-on-print text-right mt-2 mb-4">
               
                <button onclick="window.print()"
                    class="w-52 h-12 shadow-sm rounded-lg text-gray-200 border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700  dark:text-gray-400 text-base font-semibold leading-7">طباعة
                    التقرير
                </button>
            </div>
            <x-reportHeader>
                <h1 class="text-center text-xl font-semibold text-gray-900 dark:text-gray-300"> تقرير
                    الجرد </h1>
            </x-reportHeader>
            <!-- زر الطباعة -->
            {{-- <div class="no-print fixed top-4 left-4 z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md">
            <i class="fas fa-print ml-1"></i> طباعة
        </button> --}}

           
 

        <div 
        {{-- class="print-container max-w-4xl mx-auto my-8 bg-white p-8 shadow-md" --}}
        >
            <!-- ترويسة -->
            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold">مرتجع عميل</h1>
                        <p class="text-gray-600">رقم المرتجع: {{ $returnOrder->return_number }}</p>
                        <p class="text-gray-600">التاريخ:
                            {{ \Carbon\Carbon::parse($returnOrder->return_date)->format('Y-m-d') }}</p>
                    </div>

                    
                </div>
            </div>

            <!-- معلومات العميل -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">معلومات العميل</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">اسم العميل:</p>
                        <p class="font-semibold">{{ $returnOrder->customer->name ?? 'غير محدد' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">رقم الهاتف:</p>
                        <p class="font-semibold">{{ $returnOrder->customer->phone ?? 'غير محدد' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">البريد الإلكتروني:</p>
                        <p class="font-semibold">{{ $returnOrder->customer->email ?? 'غير محدد' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">العنوان:</p>
                        <p class="font-semibold">{{ $returnOrder->customer->address ?? 'غير محدد' }}</p>
                    </div>
                </div>
            </div>

            <!-- تفاصيل المرتجع -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">تفاصيل المرتجع</h2>

                <table class="w-full text-sm text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">#</th>
                            <th class="border border-gray-300 px-4 py-2">المنتج</th>
                            <th class="border border-gray-300 px-4 py-2">الكمية</th>
                            <th class="border border-gray-300 px-4 py-2">سبب الإرجاع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returnOrder->items as $index => $item)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->product->name ?? 'غير محدد' }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->quantity }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ $item->return_reason ?? $returnOrder->return_reason }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">لا توجد عناصر في
                                    هذا المرتجع</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ملاحظات -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">سبب الإرجاع</h2>
                <div class="border border-gray-300 rounded-md p-4 bg-gray-50">
                    <p>{{ $returnOrder->return_reason }}</p>
                </div>
            </div>

            <!-- التوقيعات -->
            <div class="grid grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <div class="border-t border-gray-400 pt-2">
                        <p class="font-semibold">توقيع العميل</p>
                    </div>
                </div>

                <div class="text-center">
                    <div class="border-t border-gray-400 pt-2">
                        <p class="font-semibold">توقيع المستلم</p>
                    </div>
                </div>

                <div class="text-center">
                    <div class="border-t border-gray-400 pt-2">
                        <p class="font-semibold">ختم الشركة</p>
                    </div>
                </div>
            </div>

            <!-- الشروط والأحكام -->
            <div class="mt-12 pt-6 border-t text-sm text-gray-600">
                <h3 class="font-semibold mb-2">الشروط والأحكام:</h3>
                <ol class="list-decimal list-inside space-y-1">
                    <li>يجب أن تكون المنتجات المرتجعة في حالتها الأصلية وبدون أي تلف.</li>
                    <li>يحق للشركة رفض استلام أي منتج لا يتوافق مع شروط الإرجاع.</li>
                    <li>يتم فحص المنتجات المرتجعة قبل قبولها نهائياً.</li>
                    <li>في حالة قبول الإرجاع، سيتم إضافة قيمة المنتجات إلى رصيد العميل أو استبدالها حسب الاتفاق.</li>
                </ol>
            </div>

            <!-- معلومات الشركة -->
            <div class="mt-8 pt-6 border-t text-center text-sm text-gray-600">
                <p>شركة مدار سيستمز للحلول التقنية المتكاملة</p>
                <p>هاتف: 0123456789 | البريد الإلكتروني: info@madarsys.com</p>
                <p>العنوان: المملكة العربية السعودية - الرياض</p>
            </div>
        </div>
        </div>
    </x-base>
