<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\ManufacturingCountry;
use App\Models\Brand;
use App\Models\Partner; // الموردين
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function updateBarcode(Request $request, Product $product)
    {
        $data = $request->validate([
            'barcode' => 'required|string|unique:products,barcode,' . $product->id,
        ]);
    
        $product->barcode = $data['barcode'];
        $product->save();
    
        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث الباركود بنجاح',
            'product' => $product,
        ]);
    }
    

    // عرض جميع المنتجات
    // public function index(Request $request)
    // {
    //     $query = Product::query()->with('category', 'supplier');

    //     if ($request->has('search')) {
    //         $search = $request->input('search');
    //         $query->where('name', 'like', "%$search%")
    //               ->orWhere('sku', 'like', "%$search%")
    //               ->orWhere('barcode', 'like', "%$search%")
    //               ->orWhereHas('category', function ($q) use ($search) {
    //                   $q->where('name', 'like', "%$search%");
    //               })
    //               ->orWhereHas('supplier', function ($q) use ($search) {
    //                   $q->where('name', 'like', "%$search%");
    //               });
    //     }

    //     // تصحيح استدعاء `paginate` ليتم تطبيقه على `$query`
    //     $products = $query->paginate(7);

    //     return view('products.index', compact('products')); // تأكد من أن `return` في النهاية وليس داخل `if`
    // }
    public function index(Request $request)
    {
        $query = Product::query()
            ->with('category', 'supplier', 'brand', 'manufacturingCountry'); // إضافة العلاقات 'brand' و 'manufacturingCountry'

        // البحث بناءً على الحقول المختلفة
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
                })
                ->orWhereHas('brand', function ($q) use ($search) { // البحث في العلامة التجارية
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('manufacturingCountry', function ($q) use ($search) { // البحث في بلد التصنيع
                    $q->where('name', 'like', "%$search%");
                });
        }

        // تطبيق pagination على الاستعلام
        $products = $query->paginate(7);

        // إرجاع المنتجات مع البيانات المربوطة
        return view('products.index', compact('products'));
    }


    // عرض نموذج إضافة منتج جديد
    public function create()
    {
        $categories = Category::all(); // جلب التصنيفات
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('name', 'مورد'); // تعديل للبحث عن الشركاء من نوع "مورد"
        })->get(); // جلب الموردين فقط بناءً على نوع الشريك
        $units = Unit::all(); // جلب الوحدات
        $brands = Brand::all(); // جلب العلامات التجارية
        $manufacturingCountries = ManufacturingCountry::all();

        return view('products.create', compact('categories', 'suppliers', 'units', 'brands', 'manufacturingCountries'));
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
            'brand_id' => 'nullable|exists:brands,id',
            'manufacturing_country_id' => 'nullable|exists:manufacturing_countries,id',
            'tax' => 'nullable|numeric|max:100', // الضريبة
            'discount' => 'nullable|numeric|max:100', // التخفيضات
            'supplier_contact' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'manufacturing_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'last_updated' => 'nullable|date',
            'min_stock_level' => 'nullable|integer|min:1',
            'max_stock_level' => 'nullable|integer|min:1',
            'manufacturing_country_id' => 'nullable|exists:manufacturing_countries,id',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            // الرسائل المخصصة
            'expiration_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو مساوٍ لتاريخ الإنتاج.',
        ]);
 // التحقق المخصص للتأكد من أن min_stock_level أصغر من max_stock_level
 if ($request->min_stock_level >= $request->max_stock_level) {
    return redirect()->back()->withErrors(['min_stock_level' => 'مستوى المخزون الأدنى يجب أن يكون أقل من مستوى المخزون الأقصى.'])->withInput();
}

        $data = $request->except('image');

        // معالجة رفع الصورة
        if ($request->hasFile('image')) {
            // استخدام دالة store لنقل الصورة إلى مجلد "products" داخل التخزين العام
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath; // ستكون القيمة على شكل "products/your_image.jpg"
        }

        // إنشاء السجل في قاعدة البيانات باستخدام نموذج Product
        Product::create($data);
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }


    // عرض نموذج تعديل المنتج
    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = Unit::all();
        $brands = Brand::all();
        $manufacturingCountries = ManufacturingCountry::all();

        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('name', 'مورد'); // جلب الشركاء من نوع "مورد"
        })->get();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'units', 'brands', 'manufacturingCountries'));
    }



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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // التحقق من الصورة
            'brand' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|max:100',
            'discount' => 'nullable|numeric|max:100',
            'supplier_contact' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'manufacturing_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'last_updated' => 'nullable|date',
            'max_stock_level' => 'nullable|integer|min:1',
            'min_stock_level' => 'nullable|integer|min:1',
            'manufacturing_country_id' => 'nullable|exists:manufacturing_countries,id',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            // الرسائل المخصصة
            'expiration_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو مساوٍ لتاريخ الإنتاج.',
        ]);
 // التحقق المخصص للتأكد من أن min_stock_level أصغر من max_stock_level
 if ($request->min_stock_level >= $request->max_stock_level) {
    return redirect()->back()->withErrors(['min_stock_level' => 'مستوى المخزون الأدنى يجب أن يكون أقل من مستوى المخزون الأعلى.'])->withInput();
}

        // تجميع البيانات من الطلب باستثناء الصورة
        $data = $request->except('image');

        // التحقق من وجود صورة في الطلب
        if ($request->hasFile('image')) {
            // التحقق من أن الصورة فعلاً هي صورة
            $image = $request->file('image');
            if (!$image->isValid()) {
                return redirect()->back()->with('error', 'الصورة غير صالحة.');
            }

            // في حال رغبت بحذف الصورة القديمة (إن وُجدت)
            if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
                // حذف الصورة القديمة
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }

            // تخزين الصورة في مجلد "products" باستخدام الـ disk "public"
            $imagePath = $image->store('products', 'public');

            // التحقق من أن الصورة تم تخزينها بنجاح
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل الصورة.');
            }
        }

        // تحديث المنتج
        $product->update($data);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function show($id)
    {
        $product = Product::with('category', 'supplier', 'brand', 'manufacturingCountry')->findOrFail($id);
        // dd($product); // تحقق من البيانات

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
    public function getUnits($productId)
    {
        // تحميل المنتج مع جميع مستويات الوحدات المتداخلة
        $product = Product::with(['unit.childrenRecursive', 'unit.parentRecursive'])->find($productId);
        // التحقق من وجود المنتج والوحدة
        if (!$product || !$product->unit) {
            return response()->json(['units' => []]);
        }

        // جمع الوحدات باستخدام Collection لتجنب التكرار
        $units = collect();

        // إضافة الوحدة الأساسية الخاصة بالمنتج
        $units->push($product->unit);

        // استرجاع جميع الأبناء المتداخلين
        $this->getAllChildren($product->unit, $units);

        // استرجاع جميع الآباء المتداخلين
        $this->getAllParents($product->unit, $units);

        // إزالة التكرار باستخدام unique() وتحويل النتيجة لمصفوفة
        // $uniqueUnits = $units->unique('id')->values()->all();
        $uniqueUnits = $units->unique('id')->values()->map(function ($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
            ];
        })->all();
        return response()->json(['units' => $uniqueUnits]);
    }

    /**
     * جلب جميع الوحدات الأبناء بشكل متداخل مع تجنب التكرار
     */
    private function getAllChildren($unit, &$units, &$visited = [])
    {
        // إذا تمت زيارة الوحدة بالفعل، نتجنب تكرارها
        if (in_array($unit->id, $visited)) {
            return;
        }
        // نضيف الـ ID إلى المصفوفة التي تم زيارتها
        $visited[] = $unit->id;

        // استدعاء الأطفال
        foreach ($unit->children as $child) {
            // إذا لم تمت زيارة الطفل بعد، نضيفه
            if (!in_array($child->id, $visited)) {
                $units->push($child);
                $this->getAllChildren($child, $units, $visited);
            }
        }
    }

    /**
     * جلب جميع الوحدات الآباء بشكل متداخل مع تجنب التكرار
     */
    private function getAllParents($unit, &$units)
    {
        if ($unit->parent && !$units->contains('id', $unit->parent->id)) {
            $units->push($unit->parent);
            $this->getAllParents($unit->parent, $units);
        }
    }
}
