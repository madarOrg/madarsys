<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\InventoryTransaction\InventoryValidationService;
use App\Models\TransactionType;

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
        $transactionTypeId = $this->input('transaction_type_id');
        $transactionType = TransactionType::find($transactionTypeId);
        $secondaryWarehouseId = $this->input('secondary_warehouse_id'); // تجنب الخطأ عند عدم وجوده
        $warehouseId = $this->input('warehouse_id'); // استخراج معرف المستودع
        return [
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_date' => ['required', 'date', function ($attribute, $value, $fail)  use ($warehouseId, $secondaryWarehouseId) {
                $errorMessage = $this->inventoryValidationService->validateTransactionDate($value, $warehouseId);
                if ($errorMessage) {

                    $fail($errorMessage);
                }
                // التحقق من تاريخ المخزون في المستودع الثانوي (إذا كان موجودًا)
                if ($secondaryWarehouseId) {
                    $errorMessageSecondary = $this->inventoryValidationService->validateTransactionDate($value, $secondaryWarehouseId);
                    if ($errorMessageSecondary) {
                        $fail($errorMessageSecondary);
                    }
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
            'secondary_warehouse_id' => [
                'nullable',
                'exists:warehouses,id',
                function ($attribute, $value, $fail) use ($transactionType) {
                    if ($transactionType && $transactionType->inventory_movement_count == 2) {
                        if (!$value) {
                            $fail('المستودع الثانوي مطلوب عند إجراء عملية مخزنية تتطلب مستودعين.');
                        }
                    } elseif ($value) {
                        $fail('لا يمكن تحديد مستودع ثانوي إلا إذا كانت العملية تتطلب مستودعين.');
                    }
                }
            ],
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0.01',
            'warehouse_locations' => 'nullable|array',
            'warehouse_locations.*' => 'nullable|exists:warehouse_locations,id',
        ];
    }




    /**
     * تخصيص الرسائل عند حدوث أخطاء التحقق.
     */
    public function messages()
    {
        return [
            'transaction_type_id.required' => 'نوع العملية مطلوب.',
            'transaction_type_id.exists' => 'نوع العملية المحدد غير صالح.',
            'transaction_date.required' => 'يجب تحديد تاريخ العملية.',
            'transaction_date.date' => 'يجب أن يكون التاريخ صالحًا.',
            'reference.required' => 'حقل المرجع مطلوب.',
            'reference.string' => 'يجب أن يكون المرجع نصيًا.',
            'reference.max' => 'يجب ألا يتجاوز المرجع 255 حرفًا.',
            'partner_id.required' => 'يجب تحديد الشريك.',
            'partner_id.exists' => 'الشريك المحدد غير صالح.',
            'department_id.exists' => 'القسم المحدد غير صالح.',
            'warehouse_id.required' => 'المستودع مطلوب.',
            'warehouse_id.exists' => 'المستودع المحدد غير صالح.',
            'notes.string' => 'يجب أن تكون الملاحظات نصية.',
            'products.required' => 'يجب تحديد المنتجات.',
            'products.array' => 'يجب أن يكون تنسيق المنتجات كمصفوفة.',
            'products.*.exists' => 'أحد المنتجات المحددة غير صالح.',
            'quantities.required' => 'يجب تحديد الكميات.',
            'quantities.array' => 'يجب أن يكون تنسيق الكميات كمصفوفة.',
            'quantities.*.numeric' => 'يجب أن تكون الكمية رقمًا.',
            'quantities.*.min' => 'يجب أن تكون الكمية أكبر من 0.01.',
            'warehouse_locations.array' => 'يجب أن يكون تنسيق مواقع المستودعات كمصفوفة.',
            'warehouse_locations.*.exists' => 'أحد مواقع المستودع المحددة غير صالح.',
            'secondary_warehouse_id.exists' => 'المستودع الثانوي المحدد غير صالح.',
        ];
    }
}
