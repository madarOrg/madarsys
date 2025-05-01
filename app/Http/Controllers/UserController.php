<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Role;

class UserController extends Controller
{

    // عرض قائمة المستخدمين
    public function index(Request $request)
    {
        try {
            $search = $request->input('search'); // الحصول على نص البحث
    
            // جلب المستخدمين مع الأدوار المرتبطة بهم
            $users = User::with('roles')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->paginate(7); // جلب 7 مستخدمين في كل صفحة
    
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'حدث خطأ أثناء عرض المستخدمين: ' . $e->getMessage());
        }
    }
    



    // عرض نموذج إنشاء مستخدم جديد

    public function create()
    {
        try {
            // جلب قائمة الأدوار المتاحة من جدول الأدوار
            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'حدث خطأ أثناء جلب الأدوار: ' . $e->getMessage());
        }
    }
    //تخزين بيانات المستخدم الجديد

    public function store(Request $request)
    {
        try {
            // التحقق من صحة البيانات المدخلة
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
                'role' => 'required|exists:roles,name', // التحقق من أن الدور موجود في جدول roles
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $branchId = $request->branch_id ?? auth()->user()->branch_id ?? null;

            // إنشاء المستخدم مع branch_id
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'branch_id' => $branchId, // يتم تعيين البرانش إذا كان متاحًا
            ]);

            // تعيين الدور للمستخدم
            $role = Role::where('name', $request->role)->first(); // استرجاع الدور بناءً على الاسم
            $user->roles()->attach($role->id); // ربط المستخدم بالدور

            return redirect()->route('users.create')->with('success', 'تم إنشاء الحساب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage());
        }
    }


    //  عرض تفاصيل المستخدم

    public function show(User $user)
    {
        try {
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'حدث خطأ أثناء عرض المستخدم: ' . $e->getMessage());
        }
    }

    // عرض نموذج تعديل بيانات المستخدم

    public function edit(User $user)
    {
        try {
            // جلب جميع الأدوار المتاحة من جدول الأدوار
            $roles = Role::all();

            // تمرير المستخدم والأدوار إلى الـ view
            return view('users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'حدث خطأ أثناء جلب بيانات المستخدم: ' . $e->getMessage());
        }
    }


    //تحديث بيانات المستخدم

    public function update(Request $request, User $user)
    {
        try {
            // قواعد التحقق الأساسية
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'status' => 'required|in:0,1', // (1=فعال، 0=متوقف)
            ];

            // تحقق من كلمة المرور فقط إذا كانت محدثة
            if ($request->has('password') && $request->filled('password')) {
                $rules['password'] = 'string|min:8|confirmed';
            }

            // التحقق من البيانات
            $validated = $request->validate($rules);
            // إذا كانت كلمة المرور محدثة، قم بتعيين كلمة المرور الجديدة
            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            } else {
                unset($validated['password']); // إزالة كلمة المرور إذا لم يتم تحديثها
            }

            // تحديث بيانات المستخدم
            $user->update($validated);
            if ($request->has('role')) {
                $role = Role::where('name', $request->role)->first();

                // تحقق إذا كان للمستخدم دور بالفعل
                if ($user->roles()->where('role_id', $role->id)->exists()) {
                    // إذا كان الدور موجودًا، لا حاجة لتحديثه
                    return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
                } else {
                    // إذا لم يكن الدور موجودًا، قم بتحديثه
                    $user->roles()->sync([$role->id]); // أو يمكنك استخدام attach في حالة عدم الرغبة في الحذف
                }
            }

            return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage());
        }
    }


    //حذف المستخدم

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'حدث خطأ أثناء حذف المستخدم: ' . $e->getMessage());
        }
    }
}
