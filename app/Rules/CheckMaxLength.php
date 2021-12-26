<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckMaxLength implements Rule
{
    private $label;
    private $max;
    private $currentLength;

    /**
     * Create a new rule instance.
     *
     * @param string $label
     * @param int $max
     * @return void
     */
    public function __construct($label, $max) {
        $this->label = $label;
        $this->max = $max;
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
        return empty($value) || $this->currentLength <= $this->max;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ConfigUtil::getMessage('c-002', [$this->label, $this->max, $this->currentLength]);
    }
}
