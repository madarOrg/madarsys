<?php
namespace App\View\Components;

use Illuminate\View\Component;

class ReportHeader extends Component
{
    public $company; // تعريف المتغير

    public function __construct($company = null) // تمرير الشركة عند الإنشاء
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('components.report-header');
    }
}
