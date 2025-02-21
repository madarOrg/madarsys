<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasUser
{
    public static function bootHasUser()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_user = Auth::id();
                $model->updated_user = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_user = Auth::id();
            }
        });
    }
}
