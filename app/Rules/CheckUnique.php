<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use App\Repositories\MstUserRepository;
use Illuminate\Contracts\Validation\Rule;

class CheckUnique implements Rule
{
    private $label;

    private $data;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $label, array $data = [])
    {
        $this->label = $label;
        $this->data = $data;
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
        $mstUserRepository = new MstUserRepository();
        $mstUser = $mstUserRepository->checkUnique($attribute, $value, $this->data);
        return count($mstUser) > 0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ConfigUtil::getMessage('e-003', [$this->label]);
    }
}
