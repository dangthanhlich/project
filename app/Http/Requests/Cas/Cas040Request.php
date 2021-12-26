<?php

namespace App\Http\Requests\Cas;

use App\Libs\ConfigUtil;
use Illuminate\Foundation\Http\FormRequest;

class Cas040Request extends FormRequest
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
            'receive_plan_date_from' => [
                'nullable',
                'date_format:Y/m/d',
            ],
            'receive_plan_date_to' => [
                'nullable',
                'date_format:Y/m/d',
            ],
        ];
    }

    public function messages() {
        return [
            'receive_plan_date_from.required' => ConfigUtil::getMessage('c-001', ['受入予定日']),
            'receive_plan_date_from.date_format' => ConfigUtil::getMessage('c-010', ['受入予定日']),
            'receive_plan_date_to.date_format' => ConfigUtil::getMessage('c-010', ['受入予定日']),
        ];
    }
}
