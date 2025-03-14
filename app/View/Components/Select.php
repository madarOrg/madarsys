<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $options;
    public $selectId;
    public $route; // مسار البحث الديناميكي

    /**
     * إنشاء مكون SearchableSelect.
     *
     * @param  string  $name اسم الحقل في النموذج
     * @param  string  $route مسار البحث الديناميكي
     * @param  array  $options قائمة الخيارات المبدئية
     * @param  string|null  $selectId معرف العنصر (إذا لم يُمرّر يتم توليده من الاسم)
     */
    public function __construct($name, $route, $options = [], $selectId = null)
    {
        $this->name = $name;
        $this->route = $route;
        $this->options = $options;
        $this->selectId = $selectId ?? 'select_' . $name;
    }

    public function render()
    {
        return view('components.select');
    }
}
