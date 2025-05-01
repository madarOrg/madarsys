<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ModuleAction;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if (!$user->roles()->exists()) {
        return abort(403, 'لا يوجد دور للمستخدم.');
    }

    $fullPath = '/' . ltrim($request->path(), '/'); // مثال: /orders/create أو /orders/5/edit
    $actionType = $this->inferActionType($request);

    // نبحث عن المسار الكامل المسجل في جدول module_actions
    $moduleAction = ModuleAction::where('route', $fullPath)->first();

    // إن لم نجده، نحاول نبحث عن المسار العام (مثلاً /orders) إن وجد
    if (!$moduleAction) {
        $segments = explode('/', trim($request->path(), '/'));
        $basePath = '/' . $segments[0]; // /orders
        $moduleAction = ModuleAction::where('route', $basePath)->first();
    }

    if (!$moduleAction) {
        return $next($request); // السماح إذا لم يكن معرفًا
    }

    $permissionId = optional($moduleAction->permissions->first())->id;

    if (!$permissionId) {
        return response()->view('components.404', [], 403);
    }

    $hasPermission = $user->roles->contains(function ($role) use ($permissionId, $actionType) {
        return $role->permissions()
            ->where('permissions.id', $permissionId)
            ->wherePivot("can_{$actionType}", true)
            ->exists();
    });

    if (!$hasPermission) {
        return response()->view('components.404', [], 403);
    }

    return $next($request);
}


    private function inferActionType(Request $request): string
    {
        $path = $request->path(); // ex: orders/5/edit
        $method = $request->method();

        if ($method === 'POST' && str_contains($path, 'store')) {
            return 'create';
        }

        if ($method === 'POST' && str_contains($path, 'update')) {
            return 'update';
        }

        if ($method === 'DELETE' || str_contains($path, 'delete')) {
            return 'delete';
        }

        if (str_contains($path, 'edit')) {
            return 'update';
        }

        if (str_contains($path, 'create')) {
            return 'create';
        }

        return 'view'; // الافتراضي إذا كان فقط index أو show
    }
}
