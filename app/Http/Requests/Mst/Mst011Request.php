<?php

namespace App\Http\Requests\Mst;

use App\Libs\ConfigUtil;
use App\Rules\{
    CheckMaxLength,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Mst011Request extends FormRequest
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
            'memo_jarp' => [
                new CheckMaxLength('自再協入力情報', 255),
            ],
            'memo_tr' => [
                new CheckMaxLength('運搬NW業者入力情報', 255),
            ],
        ];
    }
}
