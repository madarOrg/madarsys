<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة مرتجع المورد #{{ $returnOrder->return_number }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Cairo Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .print-container {
                width: 100%;
                max-width: 100%;
            }
            
            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- زر الطباعة -->
    <div class="no-print fixed top-4 left-4 z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md">
            <i class="fas fa-print ml-1"></i> طباعة
        </button>
        
        <a href="{{ route('returns-suppliers.show', $returnOrder->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow-md mr-2">
            <i class="fas fa-arrow-right ml-1"></i> العودة
        </a>
    </div>
    
    <div class="print-container max-w-4xl mx-auto my-8 bg-white p-8 shadow-md">
        <!-- ترويسة -->
        <div class="border-b pb-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">مرتجع مورد</h1>
                    <p class="text-gray-600">رقم المرتجع: {{ $returnOrder->return_number }}</p>
                    <p class="text-gray-600">التاريخ: {{ \Carbon\Carbon::parse($returnOrder->return_date)->format('Y-m-d') }}</p>
                </div>
                
                <div class="text-left">
                    <img src="{{ asset('images/logo.png') }}" alt="شعار الشركة" class="h-16 w-auto">
                    <h2 class="text-xl font-semibold">شركة مدار سيستمز</h2>
                    <p class="text-sm text-gray-600">للحلول التقنية المتكاملة</p>
                </div>
            </div>
        </div>
        
        <!-- معلومات المورد -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">معلومات المورد</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">اسم المورد:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->name ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600">رقم الهاتف:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->phone ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600">البريد الإلكتروني:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->email ?? 'غير محدد' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600">العنوان:</p>
                    <p class="font-semibold">{{ $returnOrder->supplier->address ?? 'غير محدد' }}</p>
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
                            <td class="border border-gray-300 px-4 py-2">{{ $item->product->name ?? 'غير محدد' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->quantity }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->return_reason ?? $returnOrder->return_reason }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">لا توجد عناصر في هذا المرتجع</td>
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
                    <p class="font-semibold">توقيع المورد</p>
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
                <li>في حالة قبول الإرجاع، سيتم خصم قيمة المنتجات من رصيد المورد أو استبدالها حسب الاتفاق.</li>
            </ol>
        </div>
        
        <!-- معلومات الشركة -->
        <div class="mt-8 pt-6 border-t text-center text-sm text-gray-600">
            <p>شركة مدار سيستمز للحلول التقنية المتكاملة</p>
            <p>هاتف: 0123456789 | البريد الإلكتروني: info@madarsys.com</p>
            <p>العنوان: المملكة العربية السعودية - الرياض</p>
        </div>
    </div>
</body>
</html>
