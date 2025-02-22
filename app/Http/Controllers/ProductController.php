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
        try {
            $products = Product::with('category', 'supplier')->paginate(7);
            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء جلب المنتجات: ' . $e->getMessage()]);
        }
    }

    // عرض نموذج إضافة منتج جديد
    public function create()
    {
        try {
            $categories = Category::all(); // الحصول على جميع التصنيفات
            $suppliers = Partner::all();   // الحصول على جميع الموردين
            return view('products.create', compact('categories', 'suppliers'));
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء جلب التصنيفات أو الموردين: ' . $e->getMessage()]);
        }
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

        try {
            // إنشاء المنتج
            Product::create($request->all());

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            // في حالة وجود خطأ، سيتم عرض رسالة خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء إضافة المنتج: ' . $e->getMessage()]);
        }
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

        try {
            // تحديث المنتج
            $product->update($request->all());

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
        } catch (\Exception $e) {
            // في حالة وجود خطأ، سيتم عرض رسالة خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage()]);
        }
    }

    // حذف المنتج من قاعدة البيانات
    public function destroy(Product $product)
    {
        try {
            // حذف المنتج
            $product->delete();

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
        } catch (\Exception $e) {
            // في حالة وجود خطأ، سيتم عرض رسالة خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage()]);
        }
    }
}
