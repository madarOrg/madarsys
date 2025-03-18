<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\InvoiceItem;
use App\Models\Branch;
use App\Models\PaymentType;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request, $type)
    {
            $viewFolder = $type === 'sale' ? 'sales' : 'purchases';
            $typeNumber = ($type === 'sale') ? 1 : 2;
            $query = Invoice::query()->where('type', $typeNumber);
            if ($request->filled('search')) {
                $query->where('invoice_code', 'like', '%' . $request->input('search') . '%');
            }

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->input('branch_id'));
            }

            if ($request->filled('partner_id')) {
                $query->where('partner_id', $request->input('partner_id'));
            }

            if ($request->filled('payment_type_id')) {
                $query->where('payment_type_id', $request->input('payment_type_id'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('invoice_date', [$request->input('start_date'), $request->input('end_date')]);
            }

            $invoices = $query->paginate(10);

            $branches = Branch::all();
            $partners = Partner::all();
            $paymentTypes = PaymentType::all();
        return view("invoices.$viewFolder.index", compact('invoices', 'branches', 'partners', 'paymentTypes'));
    }

    public function create($type)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();

        return view("invoices.$viewFolder.create", compact('partners', 'products', 'paymentTypes', 'type','Branchs'));
    }
    public function store(Request $request, $type) 
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'branch_id' => 'required|exists:branches,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount_type' => 'required|integer|in:1,2',
            'discount_value' => 'required|numeric|min:0', 
        ]);
    
        DB::beginTransaction();
        try {
            $typeNumber = ($type === 'sale') ? 1 : 2;
    
            $prefix = $typeNumber === 1 ? 'Sa-Inv-' : 'Pu-Inv-';
            $lastInvoice = Invoice::where('type', $typeNumber)->latest('id')->first();
            $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_code, strlen($prefix))) + 1 : 1;
            $invoiceCode = $prefix . $nextNumber;
    
            $discountType = (int) $request->discount_type;  
            $discountValue = (float) $request->discount_value; 
            $discountAmount = (float) ($request->discount_amount ?? 0); 

            $discountPercentage = ($discountType === 2) ? $discountValue : 0;

            $invoice = Invoice::create([
                'invoice_code' => $invoiceCode,
                'partner_id' => (int) $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'total_amount' => collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']),
                'check_number' => $request->check_number ?? 0,
                'discount_type' => $discountType,  
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage, 
                'type' => $typeNumber,
            ]);
    
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }
    
            DB::commit();
            $type = $type === 'sale' ? 'sale' : 'purchase';
            return redirect()->route('invoices.index', ['type' => $type])
                ->with('success', 'تم انشاء الفاتورة بنجاح كود الفاتورة: ' . $invoiceCode);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطاء في انشاء الفاتورة! ' . $e->getMessage());
        }
    }
    
    public function edit($type, $id)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';

        $invoice = Invoice::with('items.product')->findOrFail($id);

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();
        // dd($invoice);
        return view("invoices.$viewFolder.edit", compact('invoice', 'partners', 'Branchs', 'products', 'paymentTypes', 'type'));
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'branch_id' => 'required|exists:branches,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount_type' => 'required|integer|in:1,2',
            'discount_value' => 'required|numeric|min:0', 
        ]);
    
        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($id);
    
            $discountType = (int) $request->discount_type;
            $discountValue = (float) $request->discount_value;
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $discountPercentage = ($discountType === 2) ? $discountValue : 0;
    
            $invoice->update([
                'partner_id' => (int) $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'total_amount' => collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']),
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'check_number' => $request->check_number ?? 0,
            ]);
    
            // Delete old items and insert new ones
            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }
    
            DB::commit();
    
            $type = $type == 1 ? 'sale' : 'purchase';
    
            return redirect()->route('invoices.index', ['type' => $type])
                ->with('success', 'تم تعديل الفاتورة بنجاح!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطاء في تعديل الفاتورة! ' . $e->getMessage());
        }
    }
    

    public function destroy($type, $id)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();
            return redirect()->route('invoices.index', ['type' => $type])->with('success', 'Invoice deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting invoice!');
        }
    }
}
