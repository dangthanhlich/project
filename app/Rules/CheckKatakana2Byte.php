<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckKatakana2Byte implements Rule
{
    private $label;

    /**
     * Create a new rule instance.
     *
     * @param string $label
     * @return void
     */
    public function __construct($label) {
        $this->label = $label;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        return empty($value) || preg_match('/^[ \\p{Katakana}]*$/u', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ConfigUtil::getMessage('c-009', [$this->label]);
    }
}
