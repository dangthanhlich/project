<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckAlphaNum implements Rule
{

    private $label;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return empty($value) || preg_match('/^[a-zA-Z0-9]*$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ConfigUtil::getMessage('c-006', [$this->label]);;
    }
}
