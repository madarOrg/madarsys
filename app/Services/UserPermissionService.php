<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserPermissionService
{
    /**
     * Get user permissions from cache or database
     *
     * @return array
     */
    // public function getUserPermissions()
    // {
    //     $user = Auth::user();
    //     if (!$user) {
    //         return [];
    //     }

    //     // Generate a unique cache key for the user
    //     $cacheKey = "user_permissions_{$user->id}";

    //     // Check if data exists in cache
    //     if (Cache::has($cacheKey)) {
    //         Log::info('UserPermissionService: الصلاحيات موجودة في الكاش للمستخدم ' . $user->id);
    //         return Cache::get($cacheKey);
    //     }

    //     Log::info('UserPermissionService: جاري جلب الصلاحيات من قاعدة البيانات للمستخدم ' . $user->id);

    //     // Try to get permissions from cache, or load them if not cached
    //     return Cache::remember($cacheKey, now()->addDay(), function () use ($user) {
    //         $role = $user->roles()->first();
    //         return $role ? $role->permissions->pluck('permission_key')->toArray() : [];
    //     });
    // }
    public function getUserPermissions()
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }
    
        $cacheKey = "user_permissions_{$user->id}";
    
        if (Cache::has($cacheKey)) {
            // Log::info('UserPermissionService: الصلاحيات موجودة في الكاش للمستخدم ' . $user->id);
            return Cache::get($cacheKey);
        }
    
        // Log::info('UserPermissionService: جاري جلب الصلاحيات من قاعدة البيانات للمستخدم ' . $user->id);
    
        return Cache::remember($cacheKey, now()->addDay(), function () use ($user) {
            return $user->roles()
                ->with('permissions') // eager load permissions
                ->get()
                ->pluck('permissions') // pluck the permissions collections
                ->flatten() // flatten into a single collection
                ->pluck('permission_key') // get only the keys
                ->unique() // remove duplicates
                ->toArray(); // convert to array
        });
    }
    
    /**
     * Clear user permissions cache
     *
     * @param int $userId
     * @return void
     */
    public function clearUserPermissionsCache($userId)
    {
        Cache::forget("user_permissions_{$userId}");
        // Log::info('UserPermissionService: تم مسح كاش الصلاحيات للمستخدم ' . $userId);
    }
}
