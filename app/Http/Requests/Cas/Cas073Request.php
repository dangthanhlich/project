<?php

namespace App\Http\Requests\Cas;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    CheckNumeric,
    CheckMaxLength,
    CaseNoUnique
};
use App\Libs\ConfigUtil;

class Cas073Request extends FormRequest
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
        $rules = [
            'case_no' => [
                'required',
                new CheckNumeric('ケース番号'),  new CheckMaxLength('ケース番号', 7),
                new CaseNoUnique($this->caseId),
            ],
            'cars.*.car_no' => ['required', new CheckMaxLength('車台番号', 50)],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'case_no.required' => ConfigUtil::getMessage('c-001', ['ケース番号']),
            'cars.*.car_no.required' => ConfigUtil::getMessage('c-001', ['車台番号']),
        ];
    }
}
