<?php

namespace App\Http\Requests\Pal;

use App\Rules\CheckMaxLength;
use App\Rules\CheckNumeric;
use Illuminate\Foundation\Http\FormRequest;

class Pal020Request extends FormRequest
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
                'nullable',
                new CheckNumeric('ケース番号'),
                new CheckMaxLength('ケース番号', 7)
            ],
        ];
    }

    public function messages() {
        return [
            // 'case_no.integer' => ConfigUtil::getMessage('c-004', ['ケース番号'])
        ];
    }
}
