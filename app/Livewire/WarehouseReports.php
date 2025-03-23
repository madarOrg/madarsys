<?php

namespace App\Livewire;

use App\Models\Warehouse;
use App\Models\WarehouseReport;
use App\Exports\WarehouseReportExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use \Mpdf\Mpdf;

class WarehouseReports extends Component
{
    use WithPagination;

    public function layout()
    {
        return 'components.layouts.app';
    }

    public $warehouse_id = '';
    public $report_type = 'details';
    public $date_from;
    public $date_to;
    public $selectedReportId = null;

    protected $queryString = ['warehouse_id', 'report_type', 'date_from', 'date_to'];

    public function mount()
    {
        $this->date_from = now()->startOfMonth()->format('Y-m-d');
        $this->date_to = now()->format('Y-m-d');
    }

    public function selectReport($id)
    {
        $this->selectedReportId = $id;
    }

    public function getSelectedReportProperty()
    {
        if (!$this->selectedReportId) {
            return null;
        }
        return WarehouseReport::find($this->selectedReportId);
    }

    public function generateReport()
    {
        // dd($this->warehouse_id);
        if (!$this->warehouse_id) {
            dd('in');
            session()->flash('error', 'الرجاء اختيار المستودع أولاً');
            return;
        }
      
        try {
            $warehouse = Warehouse::with(['branch', 'supervisor', 'storageAreas','warehouseLocations'])->findOrFail($this->warehouse_id);
            $reportData = [
                'warehouse_info' => [
                    'name' => $warehouse->name,
                    'code' => $warehouse->code,
                    'address' => $warehouse->address,
                    'branch' => $warehouse->branch->name ?? 'غير محدد',
                    'supervisor' => $warehouse->supervisor->name ?? 'غير محدد',
           'areas' => $warehouse->storageAreas->map(function ($area) use ($warehouse) {
            // استرجاع الرفوف الخاصة بكل منطقة تخزين
            $shelves = $warehouse->warehouseLocations()->where('storage_area_id', $area->id)
                ->select('shelf', 'rack', 'aisle')  // تحديد الأعمدة التي نحتاجها
                ->distinct()
                ->get();

            return [
                'area_name' => $area->area_name,
                'shelves' => $shelves->map(function ($location) {
                    return [
                        'shelf' => $location->shelf,
                        'rack' => $location->rack,
                        'aisle' => $location->aisle,
                    ];
                }),
            ];
        })->toArray(),                    'capacity' => $warehouse->capacity,
                    'shelves_count' => $warehouse->shelves_count,
                    'temperature' => $warehouse->temperature,
                    'humidity' => $warehouse->humidity,
                ],
                'features' => [
                    'is_smart' => $warehouse->is_smart,
                    'has_security_system' => $warehouse->has_security_system,
                    'has_cctv' => $warehouse->has_cctv,
                    'is_integrated_with_wms' => $warehouse->is_integrated_with_wms,
                    'has_automated_systems' => $warehouse->has_automated_systems,
                ],
            ];
            if ($this->report_type === 'details') {
                // لا نحتاج لإضافة بيانات إضافية لأن معلومات المستودع موجودة بالفعل
            } elseif ($this->report_type === 'inventory') {
                $reportData['inventory'] = [
                    'total_items' => $warehouse->items()->count(),
                    'low_stock_items' => $warehouse->items()->where('quantity', '<', 10)->count(),
                    'categories' => $warehouse->items()->select('category')->distinct()->count(),
                    'total_value' => $warehouse->items()->sum('value'),
                    'last_inventory_date' => now()->format('Y-m-d'),
                ];
            } elseif ($this->report_type === 'movement') {
                $reportData['movements'] = [
                    'incoming' => $warehouse->movements()
                        ->whereBetween('created_at', [$this->date_from, $this->date_to])
                        ->where('type', 'in')
                        ->count(),
                    'outgoing' => $warehouse->movements()
                        ->whereBetween('created_at', [$this->date_from, $this->date_to])
                        ->where('type', 'out')
                        ->count(),
                    'period' => [
                        'from' => $this->date_from,
                        'to' => $this->date_to
                    ]
                ];
            }

            WarehouseReport::create([
                'warehouse_id' => $this->warehouse_id,
                'report_type' => $this->report_type,
                'report_data' => $reportData,
                'report_date' => now(),
                'generated_by' => auth()->user()->name
            ]);

            session()->flash('success', 'تم إنشاء التقرير بنجاح');
            $this->dispatch('report-generated');
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إنشاء التقرير: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        if (!$this->selectedReport) {
            return;
        }

        return Excel::download(
            new WarehouseReportExport($this->selectedReport),
            'warehouse-report-' . $this->selectedReport->id . '.xlsx'
        );
    }

    public function exportToPdf()
    {
        if (!$this->selectedReport) {
            return;
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font' => 'noto'
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $html = view('exports.warehouse-report-pdf', [
            'report' => $this->selectedReport
        ])->render();

        $mpdf->WriteHTML($html);

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, 'warehouse-report-' . $this->selectedReport->id . '.pdf');
    }

    public function viewReport($reportId)
    {
        try {
            $report = WarehouseReport::findOrFail($reportId);
            // يمكنك هنا إضافة المنطق لعرض تفاصيل التقرير
            // مثلاً: تحويل المستخدم إلى صفحة تفاصيل التقرير
            return redirect()->route('warehouse.reports.show', $reportId);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء عرض التقرير');
        }
    }

    public function render()
    {
        try {
            $warehouses = Warehouse::all();
            $reports = WarehouseReport::when($this->warehouse_id, function ($query) {
                return $query->where('warehouse_id', $this->warehouse_id);
            })
                ->when($this->report_type, function ($query) {
                    return $query->where('report_type', $this->report_type);
                })
                ->latest('report_date')
                ->paginate(10);

            return view('livewire.warehouse-reports', [
                'warehouses' => $warehouses,
                'reports' => $reports,
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء تحميل البيانات');
            return view('livewire.warehouse-reports', [
                'warehouses' => collect(),
                'reports' => collect(),
            ]);
        }
    }
}
