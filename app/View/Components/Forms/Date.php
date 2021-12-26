<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Date extends Component
{
    /**
     * input name
     *
     * @var string
     */
    public $name;

    /**
     * label name
     *
     * @var string
     */
    public $label;

    /**
     * require flag
     *
     * @var boolean|mixed
     */
    public $isRequired;

    /**
     * input value
     * 
     * @var string
     */
    public $value;

    /**
     * input id
     *
     * @var string
     */
    public $id;

    /**
     * input is label
     * 
     * @var boolean
     */
    public $isLabel;

    /**
     * input is hidden
     */
    public $isHidden;

    /**
     * data default
     */
    public $dataDefault;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, 
        $label, 
        $isRequired = false, 
        $value = '', 
        $id = '',
        $isLabel = false,
        $isHidden = false,
        $dataDefault = '',
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->isRequired = $isRequired;
        $this->value = $value;
        $this->id = $id;
        $this->isLabel = $isLabel;
        $this->isHidden = $isHidden;
        $this->dataDefault = $dataDefault;

        $this->setNameIdSelector();
    }

    public function setNameIdSelector() {
        if (empty($this->id)) {
            $this->id = str_contains($this->name, '_') ? str_replace('_', '-', $this->name) : $this->name;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.date');
    }
}
