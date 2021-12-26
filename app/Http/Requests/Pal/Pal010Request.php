<?php

namespace App\Http\Requests\Pal;

use App\Libs\{
    ConfigUtil,
    ValueUtil
};
use App\Rules\{
    CheckMaxLength,
    CheckNumeric
};
use Illuminate\Foundation\Http\FormRequest;

class Pal010Request extends FormRequest
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
            'pallet_no' => [
                'required',
                new CheckNumeric('パレット番号'),
                new CheckMaxLength('パレット番号', 6)
            ]
        ];
    }

    public function messages() {
        return [
            'pallet_no.required' => ConfigUtil::getMessage('c-001', ['パレット番号']),
        ];
    }
}
