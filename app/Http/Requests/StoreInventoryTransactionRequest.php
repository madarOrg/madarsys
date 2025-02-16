<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\InventoryTransaction\InventoryValidationService;

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
            // 'prices.*' => 'numeric|min:0.01',
            // 'totals.*' => 'numeric|min:0.01',
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
            // 'transaction_type_id.required' => 'يجب تحديد نوع العملية المخزنية.',
            // 'transaction_date.required' => 'يجب تحديد تاريخ العملية.',
            // 'products.required' => 'يجب إضافة المنتجات للعملية.',
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
        
        'prices.*.numeric' => 'يجب أن يكون السعر رقمًا.',
        'prices.*.min' => 'يجب أن يكون السعر أكبر من 0.01.',
        
        'totals.*.numeric' => 'يجب أن يكون الإجمالي رقمًا.',
        'totals.*.min' => 'يجب أن يكون الإجمالي أكبر من 0.01.',
        
        'quantities.required' => 'يجب تحديد الكميات.',
        'quantities.array' => 'يجب أن يكون تنسيق الكميات كمصفوفة.',
        'quantities.*.numeric' => 'يجب أن تكون الكمية رقمًا.',
        'quantities.*.min' => 'يجب أن تكون الكمية أكبر من 0.01.',
        
        'warehouse_locations.array' => 'يجب أن يكون تنسيق مواقع المستودعات كمصفوفة.',
        'warehouse_locations.*.exists' => 'أحد مواقع المستودع المحددة غير صالح.',
       
        ];
    }
}
