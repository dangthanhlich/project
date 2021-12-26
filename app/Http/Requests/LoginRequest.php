<?php

namespace App\Http\Requests;

use App\Libs\ConfigUtil;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'loginId'  => 'required',
            'password' => 'required',
        ];
    }

    /**
     * Validation error message
     *
     * @return array
     */
    public function messages() {
        return [
            'loginId.required'  => ConfigUtil::getMessage('c-001', ['ログインID']),
            'password.required' => ConfigUtil::getMessage('c-001', ['パスワード']),
        ];
    }
}
