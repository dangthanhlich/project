<?php

namespace App\View\Components\Html;

use Illuminate\View\Component;

class Link extends Component
{
    /**
     * href route
     * 
     * @var string
     */
    public $to;

    /**
     * label link
     * 
     * @var string
     */
    public $label;

    /**
     * label icon
     * 
     * @var mixed
     */
    public $icon;

    /**
     * link is button
     * 
     * @var boolean
     */
    public $isBtn;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($to, $label = '', $icon = null, $isBtn = false)
    {
        $this->to = $to;
        $this->label = $label;
        $this->icon = $icon;
        $this->isBtn = $isBtn;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.html.link');
    }
}
