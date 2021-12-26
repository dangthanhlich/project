<?php

namespace App\Http\Requests\Cas;

use App\Libs\ValueUtil;
use App\Rules\{
    CheckBase64Image,
    CheckBase64MaxSize,
};
use Illuminate\Foundation\Http\FormRequest;

class Cas051Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'sign_tr_2' => [
                new CheckBase64MaxSize(ValueUtil::get('File.maxFileSize')),
                new CheckBase64Image(),
            ],
            'sign_sy' => [
                new CheckBase64MaxSize(ValueUtil::get('File.maxFileSize')),
                new CheckBase64Image(),
            ],
        ];
    }

}
