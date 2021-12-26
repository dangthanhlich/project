<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Libs\ConfigUtil;
use App\Models\Cases;

class CaseNoUnique implements Rule
{
    protected $caseId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($caseId)
    {
        $this->caseId = $caseId;
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
        return !Cases::where('case_id', '!=', $this->caseId)->where('case_no', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ConfigUtil::getMessage('e-003', ['ケース番号']);
    }
}
