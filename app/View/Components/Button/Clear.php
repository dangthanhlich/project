<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;

class Clear extends Component
{

    /**
     * Label button
     * 
     * @var string
     */
    public $label;

    /**
     * Screen id
     * 
     * @var string
     */
    public $screen;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label = '', $screen = '')
    {
        $this->label = $label;
        $this->screen = $screen;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button.clear');
    }
}
