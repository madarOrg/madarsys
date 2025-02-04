<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المستودع</title>
    <style>
        body {
            line-height: 1.6;
            color: #333;
            margin: 20px;
            direction: rtl;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .header h1 {
            margin: 0;
            color: #2d3748;
            font-size: 24px;
        }
        .section {
            margin-bottom: 20px;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #e2e8f0;
            text-align: right;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #4a5568;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .value {
            font-weight: normal;
            color: #4a5568;
        }
        .label {
            color: #718096;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير المستودع</h1>
        <p>تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
    </div>

    @if($report->report_type === 'details')
        <div class="section">
            <h2 class="section-title">معلومات المستودع</h2>
            <table>
                <tr>
                    <th>الاسم</th>
                    <td>{{ $report->report_data['warehouse_info']['name'] }}</td>
                </tr>
                <tr>
                    <th>الكود</th>
                    <td>{{ $report->report_data['warehouse_info']['code'] }}</td>
                </tr>
                <tr>
                    <th>الفرع</th>
                    <td>{{ $report->report_data['warehouse_info']['branch'] }}</td>
                </tr>
                <tr>
                    <th>المشرف</th>
                    <td>{{ $report->report_data['warehouse_info']['supervisor'] }}</td>
                </tr>
                <tr>
                    <th>المساحة</th>
                    <td>{{ $report->report_data['warehouse_info']['area'] }} م²</td>
                </tr>
                <tr>
                    <th>السعة</th>
                    <td>{{ $report->report_data['warehouse_info']['capacity'] }}</td>
                </tr>
                <tr>
                    <th>عدد الرفوف</th>
                    <td>{{ $report->report_data['warehouse_info']['shelves_count'] }}</td>
                </tr>
                <tr>
                    <th>درجة الحرارة</th>
                    <td>{{ $report->report_data['warehouse_info']['temperature'] }}°C</td>
                </tr>
                <tr>
                    <th>الرطوبة</th>
                    <td>{{ $report->report_data['warehouse_info']['humidity'] }}%</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">مميزات المستودع</h2>
            <table>
                <tr>
                    <th>مستودع ذكي</th>
                    <td>{{ $report->report_data['features']['is_smart'] ? 'نعم' : 'لا' }}</td>
                </tr>
                <tr>
                    <th>نظام أمني</th>
                    <td>{{ $report->report_data['features']['has_security_system'] ? 'نعم' : 'لا' }}</td>
                </tr>
                <tr>
                    <th>كاميرات مراقبة</th>
                    <td>{{ $report->report_data['features']['has_cctv'] ? 'نعم' : 'لا' }}</td>
                </tr>
                <tr>
                    <th>متكامل مع WMS</th>
                    <td>{{ $report->report_data['features']['is_integrated_with_wms'] ? 'نعم' : 'لا' }}</td>
                </tr>
                <tr>
                    <th>أنظمة آلية</th>
                    <td>{{ $report->report_data['features']['has_automated_systems'] ? 'نعم' : 'لا' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if($report->report_type === 'inventory')
        <div class="section">
            <h2 class="section-title">تفاصيل المخزون</h2>
            <table>
                <tr>
                    <th>إجمالي المواد</th>
                    <td>{{ $report->report_data['inventory']['total_items'] }}</td>
                </tr>
                <tr>
                    <th>المواد منخفضة المخزون</th>
                    <td>{{ $report->report_data['inventory']['low_stock_items'] }}</td>
                </tr>
                <tr>
                    <th>عدد الفئات</th>
                    <td>{{ $report->report_data['inventory']['categories'] }}</td>
                </tr>
                <tr>
                    <th>القيمة الإجمالية</th>
                    <td>{{ number_format($report->report_data['inventory']['total_value'], 2) }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if($report->report_type === 'movement')
        <div class="section">
            <h2 class="section-title">تفاصيل الحركة</h2>
            <table>
                <tr>
                    <th>الفترة</th>
                    <td>{{ $report->report_data['movements']['period']['from'] }} إلى {{ $report->report_data['movements']['period']['to'] }}</td>
                </tr>
                <tr>
                    <th>عدد العمليات الواردة</th>
                    <td>{{ $report->report_data['movements']['incoming'] }}</td>
                </tr>
                <tr>
                    <th>عدد العمليات الصادرة</th>
                    <td>{{ $report->report_data['movements']['outgoing'] }}</td>
                </tr>
            </table>
        </div>
    @endif
</body>
</html>
