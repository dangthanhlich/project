<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Text extends Component
{
    /**
     * input name
     *
     * @var string
     */
    public $name;

    /**
     * input type (text|password)
     *
     * @var mixed|string
     */
    public $type;

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
     * input value hidden
     */
    public $valueHidden;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, 
        $label, 
        $isRequired = false, 
        $type = 'text', 
        $value = '', 
        $id = '',
        $isLabel = false,
        $isHidden = false,
        $valueHidden = '',
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->isRequired = $isRequired;
        $this->value = $value;
        $this->id = $id;
        $this->isLabel = $isLabel;
        $this->isHidden = $isHidden;
        $this->valueHidden = $valueHidden;

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
        return view('components.forms.text');
    }
}
