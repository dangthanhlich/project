<?php

namespace App\Http\Requests\Com;

use App\Libs\ConfigUtil;
use App\Rules\{
    CheckMaxLength,
    CheckNumeric,
};
use Illuminate\Foundation\Http\FormRequest;

class Com022Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'case_no' => [
                'required',
                new CheckMaxLength('ケース番号', 7),
            ],
            'car_nos.*' => [
                'required',
                new CheckMaxLength('車台番号', 50),
            ],
            'car_qtys.*' => [
                new CheckNumeric('回収個数'),
            ],
            'new_car_nos.*' => [
                'required',
                new CheckMaxLength('車台番号', 50),
            ],
            'new_car_qtys.*' => [
                new CheckNumeric('回収個数'),
            ]
        ];
    }

    /**
     * Validation error message
     *
     * @return array
     */
    public function messages() {
        return [
            'case_no.required' => ConfigUtil::getMessage('c-001', ['ケース番号']),
            'car_nos.*.required' => ConfigUtil::getMessage('c-001', ['車台番号']),
            'new_car_nos.*.required' => ConfigUtil::getMessage('c-001', ['車台番号']),
        ];
    }
}
