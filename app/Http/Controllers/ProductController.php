<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;

use App\Models\Partner; // الموردين
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض جميع المنتجات
    public function index(Request $request)
    {
        $query = Product::query()->with('category', 'supplier');
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%")
                  ->orWhere('barcode', 'like', "%$search%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  })
                  ->orWhereHas('supplier', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
        }
    
        // تصحيح استدعاء `paginate` ليتم تطبيقه على `$query`
        $products = $query->paginate(7);
    
        return view('products.index', compact('products')); // تأكد من أن `return` في النهاية وليس داخل `if`
    }
    

    // عرض نموذج إضافة منتج جديد
    public function create()
    {
        $categories = Category::all(); // جلب التصنيفات
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('name', 'مورد'); // تعديل للبحث عن الشركاء من نوع "مورد"
        })->get(); // جلب الموردين فقط بناءً على نوع الشريك
        $units = Unit::all(); // جلب الوحدات

        return view('products.create', compact('categories', 'suppliers', 'units'));
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
        'unit_id' => 'required|exists:units,id',
        'is_active' => 'nullable|in:0,1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // إذا كان هناك صورة للمنتج
        'brand' => 'nullable|string|max:255',
        'tax' => 'nullable|numeric|max:100', // الضريبة
        'discount' => 'nullable|numeric|max:100', // التخفيضات
        'supplier_contact' => 'nullable|string',
        'purchase_date' => 'nullable|date',
        'manufacturing_date' => 'nullable|date',
        'expiration_date' => 'nullable|date',
        'last_updated' => 'nullable|date',
        'min_stock_level' => 'nullable|integer|min:1',  // إضافة التحقق

        'max_stock_level' => 'nullable|integer|min:1',  // إضافة التحقق

        ]);

        // إنشاء المنتج
        Product::create($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }


    // عرض نموذج تعديل المنتج
    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = Unit::all();
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('name', 'مورد'); // جلب الشركاء من نوع "مورد"
        })->get();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'units'));
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
        'unit_id' => 'required|exists:units,id',
        'is_active' => 'nullable|in:0,1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // إذا كان هناك صورة للمنتج
        'brand' => 'nullable|string|max:255',
        'tax' => 'nullable|numeric|max:100', // الضريبة
        'discount' => 'nullable|numeric|max:100', // التخفيضات
        'supplier_contact' => 'nullable|string',
        'purchase_date' => 'nullable|date',
        'manufacturing_date' => 'nullable|date',
        'expiration_date' => 'nullable|date',
        'last_updated' => 'nullable|date',
        'max_stock_level' => 'nullable|integer|min:1',  // إضافة التحقق
        'min_stock_level' => 'nullable|integer|min:1',  // إضافة التحقق

        ]);

        // تحديث المنتج
        $product->update($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }
    public function show($id)
    {
        $product = Product::with('category', 'supplier')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // حذف المنتج من قاعدة البيانات
    public function destroy(Product $product)
    {
        // التحقق مما إذا كان المنتج مرتبطًا بسجلات أخرى
        //  if ($product->orders()->exists()) {
        //     return redirect()->route('products.index')->with('error', 'لا يمكن حذف المنتج لأنه مرتبط بطلبات.');
        // }

        $product->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
    ///get units of products
    public function getUnits($productId)
    {
        // تحميل المنتج مع جميع مستويات الوحدات المتداخلة
        $product = Product::with(['unit.childrenRecursive', 'unit.parentRecursive'])->find($productId);

        // التحقق من وجود المنتج والوحدة
        if (!$product || !$product->unit) {
            return response()->json(['units' => []]);
        }

        $units = [];

        // إضافة الوحدة الأساسية الخاصة بالمنتج
        $units[] = $product->unit;

        // استرجاع جميع الأبناء المتداخلين
        $this->getAllChildren($product->unit, $units);

        // استرجاع جميع الآباء المتداخلين
        $this->getAllParents($product->unit, $units);

        return response()->json(['units' => $units]);
    }

    /**
     * جلب جميع الوحدات الأبناء بشكل متداخل
     */
    private function getAllChildren($unit, &$units)
    {
        foreach ($unit->children as $child) {
            $units[] = $child;
            $this->getAllChildren($child, $units);
        }
    }

    /**
     * جلب جميع الوحدات الآباء بشكل متداخل
     */
    private function getAllParents($unit, &$units)
    {
        if ($unit->parent) {
            $units[] = $unit->parent;
            $this->getAllParents($unit->parent, $units);
        }
    }
}
