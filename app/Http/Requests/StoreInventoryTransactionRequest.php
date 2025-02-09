<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryTransactionRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم يملك الصلاحية لإجراء هذا الطلب.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // هنا يجب أن تضع منطق التحقق من صلاحية المستخدم
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
            'reference' => 'nullable|string|max:255',
            'partner_id' => 'nullable|exists:partners,id',
            'department_id' => 'nullable|exists:departments,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:1',
            'unit_prices' => 'nullable|array',
            'unit_prices.*' => 'numeric|min:0',
            'totals' => 'nullable|array',
            'totals.*' => 'numeric|min:0',
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
            // أضف المزيد من الرسائل حسب الحاجة
        ];
    }
}
