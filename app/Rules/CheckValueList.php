<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use App\Models\MstUser;
use App\Repositories\MstUserRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class CheckValueList implements Rule
{
    /**
     * input label
     */
    private $label;

    /**
     * constant list key
     */
    private $key;

    /**
     * condition to check
     */
    private $condition;

    private $data;

    /**
     * flag for edit screen
     */
    private $flag;

    private $type;

    /**
     * Create a new rule instance.
     *
     * @param string $label
     * @param string $key
     * @param string $condition
     * @param array $condition
     * @param string $flag
     * 
     * @return void
     */
    public function __construct(
        string $label,
        string $key,
        string $condition = '',
        array $data = [],
        string $flag = '',
        string $type = '',
    ) {
        $this->label = $label;
        $this->key = $key;
        $this->condition = $condition;
        $this->data = $data;
        $this->flag = $flag;
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        // empty value
        if (empty($value)) {
            return true;
        }
        // empty condition
        if (empty($this->condition)) {
            if ($this->type === 'checkbox') {
                return $this->checkForCheckbox($value);
            }
            return array_key_exists($value, ConfigUtil::getValueList($this->key));
        }
        // has condition
        if (!empty($this->condition)) {
            $conditionObj = explode(',', $this->condition);
            $field = $conditionObj[0];
            $conditionValue = $conditionObj[1];
            if ($this->flag === 'mst032_edit') {
                $mstUserRepository = new MstUserRepository();
                $mstUser = $mstUserRepository->getUser($this->data['id']);
                if ($mstUser->user_type === $conditionValue) {
                    return array_key_exists($value, ConfigUtil::getValueList($this->key));
                }
            } else {
                if ($this->type === 'checkbox') {
                    return $this->checkForCheckbox($value);
                }
                if ($this->data[$field] === $conditionValue) {
                    return array_key_exists($value, ConfigUtil::getValueList($this->key));
                }
            }
        }
        return true;
    }

    private function checkForCheckbox($value) {
        $check = true;
        foreach ($value as $val) {
            if (!array_key_exists($val, ConfigUtil::getValueList($this->key))) {
                $check = false;
            }
        }
        return $check;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ConfigUtil::getMessage('e-004', [$this->label]);
    }
}
