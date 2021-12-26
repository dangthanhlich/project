<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckMinLength implements Rule
{
    private $label;
    private $min;
    private $currentLength;

    /**
     * Create a new rule instance.
     *
     * @param string $label
     * @param int $min
     * @return void
     */
    public function __construct($label, $min) {
        $this->label = $label;
        $this->min = $min;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $this->currentLength = mb_strlen($value);
        return empty($value) || $this->currentLength >= $this->min;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ConfigUtil::getMessage('c-003', [$this->label, $this->min, $this->currentLength]);
    }
}
