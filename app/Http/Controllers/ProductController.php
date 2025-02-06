<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Partner; // الموردين
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض جميع المنتجات
    public function index()
    {
        $products = Product::with('category', 'supplier')->get();
        return view('products.index', compact('products'));
    }

    // عرض نموذج إضافة منتج جديد
    public function create()
    {
        $categories = Category::all(); // الحصول على جميع التصنيفات
        $suppliers = Partner::all();   // الحصول على جميع الموردين
        return view('products.create', compact('categories', 'suppliers'));
    }

    // تخزين منتج جديد في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'barcode' => 'nullable|string|unique:products,barcode',
            'sku' => 'required|string|unique:products,sku',
            'unit' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // إنشاء المنتج
        Product::create($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    // عرض تفاصيل المنتج
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // عرض نموذج تعديل المنتج
    public function edit(Product $product)
    {
        $categories = Category::all(); // الحصول على جميع التصنيفات
        $suppliers = Partner::all();   // الحصول على جميع الموردين
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    // تحديث المنتج في قاعدة البيانات
    public function update(Request $request, Product $product)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'unit' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // تحديث المنتج
        $product->update($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    // حذف المنتج من قاعدة البيانات
    public function destroy(Product $product)
    {
        $product->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
