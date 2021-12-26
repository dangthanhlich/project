<?php

namespace App\Http\Requests\Cas;

use App\Libs\ConfigUtil;
use App\Rules\CheckMaxLength;
use App\Rules\CheckNumeric;
use Illuminate\Foundation\Http\FormRequest;

class Cas020Request extends FormRequest
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
            'management_no' => [
                'nullable',
                new CheckNumeric('管理番号')
            ],
            'num_of_request_days_from' => [
                'nullable',
                new CheckNumeric('未集荷経過日数(From)')
            ],
            'num_of_request_days_to' => [
                'nullable',
                new CheckNumeric('未集荷経過日数(To)')
            ],
            'collect_request_time_from' => [
                'nullable',
                'date_format:Y/m/d',
            ],
            'collect_request_time_to' => [
                'nullable',
                'date_format:Y/m/d',
            ],
            'collect_complete_time_from' => [
                'nullable',
                'date_format:Y/m/d',
            ],
            'collect_complete_time_to' => [
                'nullable',
                'date_format:Y/m/d',
            ],
        ];
    }

    public function messages() {
        return [
            'collect_request_time_from.date_format' => ConfigUtil::getMessage('c-010', ['集荷依頼日']),
            'collect_request_time_to.date_format' => ConfigUtil::getMessage('c-010', ['集荷依頼日']),
            'collect_complete_time_from.date_format' => ConfigUtil::getMessage('c-010', ['集荷日']),
            'collect_complete_time_to.date_format' => ConfigUtil::getMessage('c-010', ['集荷日']),
        ];
    }
}
