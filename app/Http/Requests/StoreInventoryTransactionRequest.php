<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\InventoryValidationService;

class StoreInventoryTransactionRequest extends FormRequest
{
    protected $inventoryValidationService;

    /**
     * حقن خدمة التحقق من المخزون في الطلب
     */
    public function __construct(InventoryValidationService $inventoryValidationService)
    {
        parent::__construct();
        $this->inventoryValidationService = $inventoryValidationService;
    }

    /**
     * تحديد ما إذا كان المستخدم يملك الصلاحية لإجراء هذا الطلب.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * تحديد قواعد التحقق من الصحة.
     */
    public function rules()
    {
        return [
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_date' => ['required', 'date', function ($attribute, $value, $fail) {
                $errorMessage = $this->inventoryValidationService->validateTransactionDate($value);
                if ($errorMessage) {
                    $fail($errorMessage);
                }
            }],
            'reference' => 'required|string|max:255',
            'partner_id' => 'required|exists:partners,id',
            'department_id' => 'nullable|exists:departments,id',
            'warehouse_id' => ['required', 'exists:warehouses,id', function ($attribute, $value, $fail) {
                if (!$this->inventoryValidationService->isWarehouseActive($value)) {
                    $fail('المستودع مغلق، لا يمكنك إضافة عمليات مخزنية.');
                }
            }],
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            // 'prices.*' => ['numeric', 'min:0.01'],
            // 'totals.*' => ['numeric', 'min:0.01'],
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0.01',
            'warehouse_locations' => 'nullable|array',
            'warehouse_locations.*' =>'nullable|exists:warehouse_locations,id',
        ];
    }

    /**
     * تخصيص الرسائل عند حدوث أخطاء التحقق.
     */
    public function messages()
    {
        return [
            'transaction_type_id.required' => 'يجب تحديد نوع العملية المخزنية.',
            'transaction_date.required' => 'يجب تحديد تاريخ العملية.',
            'products.required' => 'يجب إضافة المنتجات للعملية.',
        ];
    }
}
