<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $id;
    public $options;
    public $selected;

    public function __construct($name, $id, $options, $selected = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.select');
    }
}
