<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * إنشاء مثيل جديد من المكون.
     *
     * يمكن تمرير معطيات هنا إذا أردت تخصيص سلوك المكون.
     * في هذا المثال لم يتم تمرير معطيات إضافية.
     *
     * @return void
     */
    public function __construct()
    {
        // يمكنك تهيئة المتغيرات أو المعطيات هنا إذا لزم الأمر.
    }

    /**
     * استرجاع العرض (View) الذي يمثل هذا المكون.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // يعيد العرض الخاص بالناف بار الموجود في resources/views/navbar/navbar.blade.php
        return view('navbar.navbar');
    }
}
