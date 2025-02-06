<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Partner;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض قائمة المنتجات
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();
        return view('inventory.products.index', compact('products'));
    }

    // عرض نموذج إضافة منتج جديد
    public function create()
    {
        $categories = Category::all();
        $suppliers = Partner::where('type', 'supplier')->get();
        return view('inventory.products.create', compact('categories', 'suppliers'));
    }

    // تخزين منتج جديد
    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:partners,id',
            'barcode' => 'nullable|string|unique:products,barcode',
            'sku' => 'required|string|unique:products,sku',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'min_stock_level' => 'required|integer',
            'max_stock_level' => 'nullable|integer',
            'unit' => 'required|string|max:50',
            'is_active' => 'required|boolean'
        ]);

        // إنشاء منتج جديد
        Product::create($request->all());

        return redirect()->route('inventory.products.index')->with('success', 'Product created successfully');
    }

    // عرض بيانات منتج للتعديل
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Partner::where('type', 'supplier')->get();
        return view('inventory.products.edit', compact('product', 'categories', 'suppliers'));
    }

    // تحديث بيانات المنتج
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // التحقق من صحة المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:partners,id',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'min_stock_level' => 'required|integer',
            'max_stock_level' => 'nullable|integer',
            'unit' => 'required|string|max:50',
            'is_active' => 'required|boolean'
        ]);

        // تحديث المنتج
        $product->update($request->all());

        return redirect()->route('inventory.products.index')->with('success', 'Product updated successfully');
    }

    // حذف المنتج
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('inventory.products.index')->with('success', 'Product deleted successfully');
    }
}
