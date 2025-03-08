<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseInvoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\PaymentType;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Paginate the purchase invoices with search functionality
        $purchaseInvoices = PurchaseInvoice::with('supplier', 'items') // Include supplier and purchase invoice items
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                
                // Search in the PurchaseInvoice table fields
                $query->where('invoice_date', 'like', '%' . $search . '%')
                      ->orWhere('total_amount', 'like', '%' . $search . '%')
                      
                      // Search in the related supplier's name
                      ->orWhereHas('supplier', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      })
                      
                      // Search in the related purchase invoice items' product name
                      ->orWhereHas('items', function ($q) use ($search) {
                          $q->whereHas('product', function ($q) use ($search) {
                              $q->where('name', 'like', '%' . $search . '%');
                          });
                      });
            })
            ->paginate(10);  // Adjust the number of items per page
    
        return view('purchase_invoices.index', compact('purchaseInvoices'));
    }
    

    public function create()
    {
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 1); // يفترض أن 1 هو نوع المورد
        })->select('id', 'name')->get();

        $products = Product::select('id', 'name', 'purchase_price')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();

        return view('purchase_invoices.create', compact('suppliers', 'products', 'paymentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = PurchaseInvoice::create([
                'supplier_id' => $request->supplier_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'total_amount' => collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']),
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
            return redirect()->route('purchase_invoices.index')->with('success', 'تم إنشاء فاتورة المشتريات بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الفاتورة!');
        }
    }

    public function edit($id)
    {
        $invoice = PurchaseInvoice::with('items.product')->findOrFail($id);
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 1);
        })->select('id', 'name')->get();

        $products = Product::select('id', 'name', 'purchase_price')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();

        return view('purchase_invoices.edit', compact('invoice', 'suppliers', 'products', 'paymentTypes'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = PurchaseInvoice::findOrFail($id);
            $totalAmount = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']);

            $invoice->update([
                'supplier_id' => $request->supplier_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'total_amount' => $totalAmount,
            ]);

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
            return redirect()->route('purchase_invoices.index')->with('success', 'تم تحديث الفاتورة بنجاح!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء التحديث.']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $invoice = PurchaseInvoice::findOrFail($id);
            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();
            return redirect()->route('purchase_invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف!');
        }
    }
}
