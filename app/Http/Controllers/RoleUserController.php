<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

use App\Models\RoleUser;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('roles')->get();
            $roles = Role::all();
            return view('users-roles.index', compact('users', 'roles'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب المستخدمين والأدوار: ' . $e->getMessage()], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'role_id' => 'required|exists:roles,id',
    //     ]);

    //     $user = User::find($request->user_id);
    //     $user->roles()->syncWithoutDetaching([$request->role_id]);

    //     return redirect()->route('users-roles.index')->with('success', 'تمت إضافة الدور للمستخدم بنجاح.');
    // }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = User::findOrFail($request->user_id);

            // تحقق إذا كان الدور موجودًا مسبقًا
            if ($user->roles()->where('role_id', $request->role_id)->exists()) {
                return redirect()->back()->with('error', 'هذا الدور مضاف بالفعل للمستخدم.');
            }

            // إذا لم يكن موجودًا، قم بالإضافة
            $user->roles()->attach($request->role_id);

            return redirect()->back()->with('success', 'تمت إضافة الدور بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الدور للمستخدم: ' . $e->getMessage());
        }
    }
    /**
     * حذف دور من مستخدم معين.
     *
     * @param int $userId
     * @param int $roleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($userId, $roleId)
    {
        try {
            // البحث عن سجل الربط وحذفه
            $roleUser = RoleUser::where('user_id', $userId)
                ->where('role_id', $roleId)
                ->delete();

            if ($roleUser) {
                return redirect()->back()->with('success', 'تم حذف الدور بنجاح.');
            }

            return redirect()->back()->with('error', 'تعذر العثور على السجل.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدور من المستخدم: ' . $e->getMessage());
        }
    }
}
