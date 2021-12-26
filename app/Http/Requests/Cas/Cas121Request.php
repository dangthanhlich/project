<?php

namespace App\Http\Requests\Cas;

use App\Libs\{
    ConfigUtil,
    ValueUtil
};
use App\Rules\{
    CheckNumeric
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class Cas121Request extends FormRequest
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
            'actual_qty_rp' => [
                new CheckNumeric('合計個数（非該当品を除く）'),
            ],
            'mismatch_qty_1' => [
                new CheckNumeric('短絡不良数量'),
            ],
            'mismatch_qty_2' => [
                new CheckNumeric('M式未ロック数量'),
            ],
            'mismatch_qty_3' => [
                new CheckNumeric('M式未収納数量'),
            ],
        ];
    }
}
