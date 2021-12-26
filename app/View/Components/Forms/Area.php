<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Area extends Component
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
     * class of div parent of select
     * 
     * @var string|mixed
     */
    public $divClass;

    /**
     * onChange function
     * 
     * @var string|mixed
     */
    public $onChange;

    /**
     * no default value
     * 
     * @var boolean|mixed
     */
    public $noDefault;

    /**
     * input select search
     * 
     * @var boolean
     */
    public $isSearch;

    /**
     * show area code
     * 
     * @var boolean
     */
    public $showAreaCode;

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
        $divClass = '',
        $onChange = '',
        $noDefault = false,
        $isSearch = false,
        $showAreaCode = false,
    )
    {
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->isRequired = $isRequired;
        $this->id = $id;
        $this->keySelected = $keySelected;
        $this->isLabel = $isLabel;
        $this->isHidden = $isHidden;
        $this->divClass = $divClass;
        $this->onChange = $onChange;
        $this->noDefault = $noDefault;
        $this->isSearch = $isSearch;
        $this->showAreaCode = $showAreaCode;

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
        return view('components.forms.area');
    }
}
