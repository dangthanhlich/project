<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * label name
     *
     * @var string
     */
    public $label;

    /**
     * required flag
     *
     * @var boolean|mixed
     */
    public $isRequired;

    /**
     * value list for checkbox
     *
     * @var array
     */
    public $options;

    /**
     * input name
     *
     * @var string
     */
    public $name;

    /**
     * input value checked
     * 
     * @var array
     */
    public $valueChecked;

    /**
     * input id selector
     * 
     * @var string
     */
    public $idSelector;

    /**
     * input is label
     * 
     * @var boolean
     */
    public $isLabel;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label,
        $name,
        $options,
        $isRequired = false,
        $valueChecked = [],
        $isLabel = false,
    )
    {
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->isRequired = $isRequired;
        $this->valueChecked = $valueChecked;
        $this->isLabel = $isLabel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.checkbox');
    }
}
