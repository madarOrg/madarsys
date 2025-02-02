<?php
namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Facades\Route;

class NavbarService
{
    public function getNavbarLinks($permissions)
    {
        // جلب جميع الوحدات مع الإجراءات المرتبطة بها
        $modules = Module::with(['actions.permissions'])
            ->get()
            ->map(function ($module) use ($permissions) {
                // تصفية الإجراءات (children) بناءً على الصلاحيات
                $filteredActions = $module->actions
                    ->filter(function ($action) use ($permissions) {
                        return $action->permissions
                            ->pluck('permission_key')
                            ->intersect($permissions)
                            ->isNotEmpty();
                    })
                    ->map(function ($action) {
                        return [
                            'text'  => $action->name,
                            'href'  => Route::has($action->permissions->first()?->permission_key)
                                ? route($action->permissions->first()?->permission_key)
                                : ($action->route ?? '#'),
                            'icon'  => $action->icon ?? '', // إضافة الأيقونة إذا كانت موجودة
                        ];
                    })
                    ->values();

                return [
                    'text'     => $module->name,
                    'key'      => $module->key,
                    'href'     => Route::has($module->key) ? route($module->key) : '#',
                    'children' => $filteredActions,
                ];
            })
            // تصفية الوحدات التي ليس لديها إجراءات مسموح بها
            ->filter(function ($module) {
                return count($module['children']) > 0;
            })
            ->values();

        return $modules;
    }
}

