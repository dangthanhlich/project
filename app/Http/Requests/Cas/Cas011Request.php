<?php

namespace App\Http\Requests\Cas;

use App\Libs\ConfigUtil;
use App\Rules\{
    CheckMaxLength,
    CheckNumeric,
    CheckValueList,
};
use Illuminate\Foundation\Http\FormRequest;

class Cas011Request extends FormRequest
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
            'collect_receive_plan_date' => [
                'required',
                'date_format:Y/m/d',
            ],
            'case_qty' => [
                'nullable',
                new CheckNumeric('ケース数'),
                new CheckMaxLength('ケース数', 9),
            ],
            'collect_plan_memo' => [
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
            'plan_date_adjusted_flg' => [
                new CheckValueList('予定日調整', 'PlanCase.scheduledDateAdjustment', '', [], '', 'checkbox'),
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
            'collect_receive_plan_date.required' => ConfigUtil::getMessage('c-001', ['予定日']),
            'collect_receive_plan_date.date_format' => ConfigUtil::getMessage('c-010', ['予定日']),
        ];
    }
}
