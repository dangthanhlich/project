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

class Cas060Request extends FormRequest
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
            'temp_case_no' => [
                'required',
                new CheckNumeric('ケース番号'),
                new CheckMaxLength('ケース番号', 7)
            ],
            'case_picture_3' => [
                'required',
                new CheckBase64MaxSize(ValueUtil::get('File.maxFileSize')),
            ]
        ];
    }

    public function messages() {
        return [
            'temp_case_no.required' => ConfigUtil::getMessage('c-001', ['ケース番号']),
            'case_picture_3.required' => ConfigUtil::getMessage('c-001', ['ケース写真']),
        ];
    }
}
