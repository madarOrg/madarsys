<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    // عرض قائمة الفئات
    public function index()
    {
        try {
            $categories = Category::paginate(7);
        return view('categories.index', compact('categories'));
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'حدث خطأ أثناء تحميل الفئات: ' . $e->getMessage());
        }
    }

    // عرض نموذج إضافة فئة جديدة
    public function create()
    {
        try {
            return view('categories.create');
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'حدث خطأ أثناء تحميل نموذج إضافة الفئة: ' . $e->getMessage());
        }
    }

    // تخزين فئة جديدة
    public function store(Request $request)
    {
        try {
            // التحقق من صحة المدخلات
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ]);

            // إنشاء الفئة الجديدة
            Category::create($request->all());

            return redirect()->route('categories.index')->with('success', 'تم إنشاء الفئة بنجاح');
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'حدث خطأ أثناء إضافة الفئة: ' . $e->getMessage());
        }
    }

    // عرض بيانات فئة للتعديل
    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('categories.edit', compact('category'));
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'حدث خطأ أثناء تحميل بيانات الفئة للتعديل: ' . $e->getMessage());
        }
    }

    // تحديث بيانات الفئة
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            // التحقق من صحة المدخلات
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
            ]);

            // تحديث الفئة
            $category->update($request->all());

            return redirect()->route('categories.index')->with('success', 'تم تحديث الفئة بنجاح');
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'حدث خطأ أثناء تحديث الفئة: ' . $e->getMessage());
        }
    }

    // حذف الفئة
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'تم حذف الفئة بنجاح');
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'حدث خطأ أثناء حذف الفئة: ' . $e->getMessage());
        }
    }
}
