<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;

class Back extends Component
{
    /**
     * button label
     *
     * @var string
     */
    public $label;

    /**
     * button id
     *
     * @var string
     */
    public $id;

    /**
     * href attribute for tag a
     *
     * @var string
     */
    public $href;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $href, $id = '')
    {
        $this->label = $label;
        $this->href = $href;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button.back');
    }
}
