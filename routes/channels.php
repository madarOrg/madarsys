<?php

// use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


use Illuminate\Support\Facades\Broadcast;
Broadcast::channel('private-notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId; // السماح فقط للمستخدم الذي يطابق `user_id`
});
// Broadcast::channel('notifications.{userId}', function ($user, $userId) {
//     return (int) $user->id === (int) $userId;
// });
