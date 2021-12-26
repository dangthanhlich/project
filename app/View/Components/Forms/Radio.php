<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Radio extends Component
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
     * value list for select option
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
     * input id
     *
     * @var string
     */
    public $id;

    /**
     * selected key
     * 
     * @var string|int
     */
    public $keySelected;

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
        $label,
        $name,
        $options,
        $isRequired = false,
        $id = '',
        $keySelected = '',
        $isLabel = false,
        $isHidden = false,
        $dataDefault = '',
    )
    {
        $this->label = $label;
        $this->isRequired = $isRequired;
        $this->options = $options;
        $this->name = $name;
        $this->id = $id;
        $this->keySelected = $keySelected;
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
        return view('components.forms.radio');
    }
}
