<?php

namespace App\Http\Requests\Cas;

use Illuminate\Foundation\Http\FormRequest;
use App\Libs\ConfigUtil;
use App\Rules\CheckNumeric;

class Cas071Request extends FormRequest
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
            'cars.*.qty' => ['required', new CheckNumeric('回収個数')],
            'cars.*.picture' => 'required',
            'is_mismatch' => 'required',
        ];

        if (request()->is_mismatch == 1) {
            $rules['mismatch_types.*.mismatch_qty'] = [new CheckNumeric('')];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cars.*.qty.required' => ConfigUtil::getMessage('e-005'),
            'cars.*.picture.required' => ConfigUtil::getMessage('e-006'),
            'is_mismatch.required' => ConfigUtil::getMessage('c-001', ['未合致区分の選択']),
            'mismatch_types.*.mismatch_qty.numeric' => ConfigUtil::getMessage('c-004', [''])
        ];
    }
}
