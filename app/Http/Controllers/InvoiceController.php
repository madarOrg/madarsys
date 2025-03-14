<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceItem;
use App\Models\PaymentType;

use Illuminate\Support\Facades\Log; // Make sure this line is added


class InvoiceController extends Controller
{
    // عرض صفحة إنشاء الفاتورة
    // عرض صفحة إنشاء الفاتورة
    public function create()
    {
        // Get only 'id' and 'name' for customers
        $customers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 2);  // Assuming '2' refers to the customer type
        })
            ->select('id', 'name')  // Only 'id' and 'name'
            ->get();

        // Get only 'id' and 'name' for products
        $products = Product::select('id', 'name', 'selling_price')->get();

        // Get payment types
        $paymentTypes = PaymentType::select('id', 'name')->get();

        return view('invoices.create', compact('customers', 'products', 'paymentTypes'));
    }


    //قائمة بالفواتير
    public function index(Request $request)
    {
        // Paginate the invoices
        $invoices = Invoice::when($request->search, function ($query) use ($request) {
            $search = $request->search;

            // Apply the like conditions for multiple fields in the invoices table
            $query->where('invoice_date', 'like', '%' . $search . '%')
                ->orWhere('total_amount', 'like', '%' . $search . '%')
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%'); // Searching for the customer name in the partners table
                });
        })
            ->paginate(10);  // You can adjust the number of items per page

        return view('invoices.index', compact('invoices'));
    }

    // تخزين بيانات الفاتورة في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction(); // 🔹 Start transaction
        try {
            // Create the invoice
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'total_amount' => collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']),
            ]);

            // Save invoice items
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit(); // 🔹 Commit transaction (save everything)

            return redirect()->route('invoices.create')->with('success', 'تم إنشاء الفاتورة بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack(); // 🔹 Rollback if something goes wrong
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الفاتورة!');
        }
    }
    //عرض شاشة تعديل الفاتورة
    public function edit($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);
        $customers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 2);
        })
            ->select('id', 'name')->get();

        $products = Product::select('id', 'name', 'selling_price')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();

        return view('invoices.edit', compact('invoice', 'customers', 'products', 'paymentTypes'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'customer_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Begin a transaction to ensure atomic operations
        DB::beginTransaction();

        try {
            $invoice = Invoice::findOrFail($id);

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->input('items') as $itemData) {
                $totalAmount += $itemData['quantity'] * $itemData['price'];
            }

            // Update the main invoice details
            $invoice->update([
                'customer_id' => $request->input('customer_id'),
                'invoice_date' => $request->input('invoice_date'),
                'payment_type_id' => $request->input('payment_type_id'),
                'total_amount' => $totalAmount,
                'updated_at' => now(),
            ]);


            $existingItemIds = $invoice->items()->pluck('id')->toArray();
            $updatedItemIds = [];

            foreach ($request->input('items') as $index => $itemData) {
                if (isset($itemData['id']) && $itemData['id']) {
                    // Update existing item
                    $item = InvoiceItem::findOrFail($itemData['id']);
                    $item->update([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price'],
                        'subtotal' => $itemData['quantity'] * $itemData['price'],
                    ]);
                    $updatedItemIds[] = $itemData['id']; // Add updated item ID to the list
                } else {
                    // Add new item
                    $newItem = $invoice->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price'],
                        'subtotal' => $itemData['quantity'] * $itemData['price'],
                    ]);
                    $updatedItemIds[] = $newItem->id; // Add new item ID to the list
                }
            }

            // **Find and delete removed items**
            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            InvoiceItem::whereIn('id', $itemsToDelete)->delete();

            DB::commit(); // Commit transaction

            return redirect()->route('invoices.index')->with('success', 'تم تعديل الفاتورة بنجاح');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollback();

            // Log the exception message for debugging
            Log::error('Error updating invoice: ' . $e->getMessage());

            // Optionally, you can also log the entire exception details
            Log::error($e);

            // Return an error response
            return back()->withErrors(['error' => 'An error occurred while updating the invoice. Please try again.']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction(); // Start a database transaction

        try {
            $invoice = Invoice::findOrFail($id); // Find the invoice

            // Delete all related invoice items first (to maintain referential integrity)
            $invoice->items()->delete();

            // Delete the invoice itself
            $invoice->delete();

            DB::commit(); // Commit the transaction

            return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
