<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * button label
     *
     * @var string
     */
    public $label;

    /**
     * href
     *
     * @var string
     */
    public $href;

    /**
     * button type
     *
     * @var mixed|string
     */
    public $type;

    /**
     * button name
     */
    public $name;

    /**
     * button id
     *
     * @var mixed|string
     */
    public $id;

    /**
     * button disabled
     * 
     * @var boolean
     */
    public $disabled;

     /**
     * button value
     * 
     *  @var mixed|string
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label, 
        $href = '', 
        $id = '', 
        $type = 'button', 
        $name = '',
        $disabled = false,
        $value = ''
    )
    {
        $this->label = $label;
        $this->href = $href;
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->disabled = $disabled;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}
