<?php

namespace App\Http\Requests\Cas;

use App\Libs\ConfigUtil;
use App\Rules\CheckNumeric;
use Illuminate\Foundation\Http\FormRequest;

class Cas030Request extends FormRequest
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
            'management_no' => [
                'nullable',
                new CheckNumeric('管理番号'),
            ],
        ];
    }

    /**
     * Validation error message
     *
     * @return array
     */
    public function messages() {
        return [
            // 'management_no.integer' => ConfigUtil::getMessage('c-004', ['管理番号']),
        ];
    }
}
