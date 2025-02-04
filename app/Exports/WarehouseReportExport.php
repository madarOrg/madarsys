<?php

namespace App\Exports;

use App\Models\WarehouseReport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseReportExport implements FromArray, WithHeadings, WithStyles
{
    protected $report;

    public function __construct(WarehouseReport $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        $data = [];
        
        if ($this->report->report_type === 'details') {
            $info = $this->report->report_data['warehouse_info'];
            $features = $this->report->report_data['features'];
            
            $data = [
                [
                    'المعلومات الأساسية',
                    '',
                    '',
                ],
                [
                    'الاسم',
                    $info['name'],
                    '',
                ],
                [
                    'الكود',
                    $info['code'],
                    '',
                ],
                [
                    'الفرع',
                    $info['branch'],
                    '',
                ],
                [
                    'المشرف',
                    $info['supervisor'],
                    '',
                ],
                [
                    'المساحة',
                    $info['area'] . ' م²',
                    '',
                ],
                [
                    'السعة',
                    $info['capacity'],
                    '',
                ],
                [
                    'عدد الرفوف',
                    $info['shelves_count'],
                    '',
                ],
                [
                    'درجة الحرارة',
                    $info['temperature'] . '°C',
                    '',
                ],
                [
                    'الرطوبة',
                    $info['humidity'] . '%',
                    '',
                ],
                [
                    'المميزات',
                    '',
                    '',
                ],
                [
                    'مستودع ذكي',
                    $features['is_smart'] ? 'نعم' : 'لا',
                    '',
                ],
                [
                    'نظام أمني',
                    $features['has_security_system'] ? 'نعم' : 'لا',
                    '',
                ],
                [
                    'كاميرات مراقبة',
                    $features['has_cctv'] ? 'نعم' : 'لا',
                    '',
                ],
                [
                    'متكامل مع WMS',
                    $features['is_integrated_with_wms'] ? 'نعم' : 'لا',
                    '',
                ],
                [
                    'أنظمة آلية',
                    $features['has_automated_systems'] ? 'نعم' : 'لا',
                    '',
                ],
            ];
        } elseif ($this->report->report_type === 'inventory') {
            $inventory = $this->report->report_data['inventory'];
            
            $data = [
                [
                    'إجمالي المواد',
                    $inventory['total_items'],
                    '',
                ],
                [
                    'المواد منخفضة المخزون',
                    $inventory['low_stock_items'],
                    '',
                ],
                [
                    'عدد الفئات',
                    $inventory['categories'],
                    '',
                ],
                [
                    'القيمة الإجمالية',
                    number_format($inventory['total_value'], 2),
                    '',
                ],
            ];
        } elseif ($this->report->report_type === 'movement') {
            $movements = $this->report->report_data['movements'];
            
            $data = [
                [
                    'الفترة',
                    $movements['period']['from'] . ' إلى ' . $movements['period']['to'],
                    '',
                ],
                [
                    'عدد العمليات الواردة',
                    $movements['incoming'],
                    '',
                ],
                [
                    'عدد العمليات الصادرة',
                    $movements['outgoing'],
                    '',
                ],
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'البيان',
            'القيمة',
            '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['font' => ['bold' => true]],
        ];
    }
}
