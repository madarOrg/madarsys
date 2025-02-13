<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Warehouse;

class StoreInventoryTransactionRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم يملك الصلاحية لإجراء هذا الطلب.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * تحديد قواعد التحقق من الصحة.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_date' => 'required|date',
            'reference' => 'required|string|max:255',
            'partner_id' => 'required|exists:partners,id',
            'department_id' => 'nullable|exists:departments,id',
            'warehouse_id' => ['required', 'exists:warehouses,id', 
                function ($attribute, $value, $fail) {
                $this->validateWarehouseStatus($value, $fail);
            }],
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0.01',
            'warehouse_locations' => 'nullable|array',
            'warehouse_locations.*' => 'exists:warehouse_locations,id',
        ];
    }

    /**
     * تخصيص الرسائل عند حدوث أخطاء التحقق.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'transaction_type_id.required' => 'يجب تحديد نوع العملية المخزنية.',
            'transaction_date.required' => 'يجب تحديد تاريخ العملية.',
            'products.required' => 'يجب إضافة المنتجات للعملية.',
        ];
    }

    /**
     * دالة للتحقق مما إذا كان المستودع نشطًا أم لا.
     *
     * @param int $warehouseId
     * @param callable $fail
     */
    private function validateWarehouseStatus($warehouseId, $fail)
    {
        $warehouse = Warehouse::find($warehouseId);

        if ($warehouse && !$warehouse->is_active) {
            $fail('المستودع مغلق، لا يمكنك إضافة عمليات مخزنية.');
        }
    }
}
