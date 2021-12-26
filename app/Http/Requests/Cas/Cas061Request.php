<?php

namespace App\Http\Requests\Cas;

use App\Libs\{
    ConfigUtil,
    ValueUtil
};
use App\Rules\{
    CheckMaxLength,
    CheckBase64MaxSize,
    CheckNumeric
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class Cas061Request extends FormRequest
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
                new CheckNumeric('ケース番号'),
                new CheckMaxLength('ケース番号', 7)
            ],
            'car_no' => [
                'sometimes',
                'required',
                new CheckNumeric('車台番号（追加）'),
            ],
            'case_picture_2' => [
                'required',
                new CheckBase64MaxSize(ValueUtil::get('File.maxFileSize')),
            ]
        ];
    }

    public function messages() {
        return [
            'car_no.required' => ConfigUtil::getMessage('c-001', ['車台番号（追加）']),
            'case_picture_2.required' => ConfigUtil::getMessage('c-001', ['荷札写真']),
        ];
    }
}
