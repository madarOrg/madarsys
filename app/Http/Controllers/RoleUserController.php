<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('users-roles.index', compact('users', 'roles'));
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
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        $user = User::findOrFail($request->user_id);
        $user->roles()->attach($request->role_id);
        return redirect()->back()->with('success', 'تمت إضافة الدور بنجاح');
    }
    
    public function destroy($userId, $roleId)
    {
        $user = User::find($userId);
        $user->roles()->detach($roleId);

        return redirect()->route('users-roles.index')->with('success', 'تمت إزالة الدور من المستخدم بنجاح.');
    }
}

