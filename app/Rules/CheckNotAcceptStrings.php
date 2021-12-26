<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckNotAcceptStrings implements Rule
{
    private $notAcceptStrings;

    /**
     * Create a new rule instance.
     *
     * @param array $notAcceptStrings
     * @return void
     */
    public function __construct($notAcceptStrings) {
        $this->notAcceptStrings = $notAcceptStrings;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        foreach ($this->notAcceptStrings as $notAcceptString) {
            if (strpos($value, $notAcceptString) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return 'Value not valid.';
    }
}
