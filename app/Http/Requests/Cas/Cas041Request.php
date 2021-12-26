<?php

namespace App\Http\Requests\Cas;

use App\Libs\ConfigUtil;
use App\Rules\CheckDate;
use App\Rules\CheckMaxLength;
use App\Rules\CheckNumeric;
use Illuminate\Foundation\Http\FormRequest;

class Cas041Request extends FormRequest
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
            'receive_plan_date' => [
                'required',
                'date_format:Y/m/d',
                // new CheckDate('予定日'),
            ],
            'case_qty' => [
                'nullable',
                new CheckNumeric('ケース数'),
                new CheckMaxLength('ケース数', 9),
            ],
            'receive_plan_memo' => [
                'nullable',
                new CheckMaxLength('メモ', 255),
            ],
            'empty_case_qty' => [
                'nullable',
                new CheckNumeric('空ケース数'),
                new CheckMaxLength('空ケース数', 9),
            ],  
            'bag_qty' => [
                'nullable',
                new CheckNumeric('空袋数'),
                new CheckMaxLength('空袋数', 9),
            ],
        ];
    }

    public function messages() {
        return [
            'receive_plan_date.required' => ConfigUtil::getMessage('c-001', ['予定日']),
            'receive_plan_date.date_format' => ConfigUtil::getMessage('c-010', ['予定日']),
            // 'case_qty.integer' => ConfigUtil::getMessage('c-004', ['ケース数']),
            // 'empty_case_qty.integer' => ConfigUtil::getMessage('c-004', ['空ケース数']),
            // 'bag_qty.integer' => ConfigUtil::getMessage('c-004', ['空袋数']),
        ];
    }
}
