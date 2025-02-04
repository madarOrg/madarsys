<?php
namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NavbarService
{
    public function getNavbarLinks($permissions)
    {
        // Generate a unique cache key based on the user's permissions
        $cacheKey = 'navbar_links_' . md5(json_encode($permissions));

        // Get from cache or generate
        return Cache::remember($cacheKey, now()->addDay(), function () use ($permissions) {
            Log::info('NavbarService: جاري إنشاء القائمة من قاعدة البيانات');
            
            // جلب جميع الوحدات مع الإجراءات المرتبطة بها
            return Module::with(['actions.permissions'])
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
                                'icon'  => $action->icon ?? '',
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
                ->filter(function ($module) {
                    return count($module['children']) > 0;
                })
                ->values();
        });
    }

    public function clearNavbarCache($permissions)
    {
        $cacheKey = 'navbar_links_' . md5(json_encode($permissions));
        Cache::forget($cacheKey);
        Log::info('NavbarService: تم مسح الكاش للقائمة');
    }
}
