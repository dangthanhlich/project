<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckMailRFC implements Rule
{
    /**
     * Check Email RFC type
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return empty($value) || preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $value) && preg_match('/^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,.\/:;"\'{}]*$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ConfigUtil::getMessage('c-007');
    }
}
