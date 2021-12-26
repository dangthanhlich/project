<?php

namespace App\Http\Requests\Mst;

use App\Libs\ConfigUtil;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    CheckAlphaNum,
    CheckMailRFC,
    CheckMaxLength,
    CheckMinLength,
    CheckValueList,
};
use Illuminate\Http\Request;

class Mst032Request extends FormRequest
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
    public function rules(Request $request)
    {
        $jarpTypeDataCheck = [
            'id' => $request->route('id'),
        ];
        
        return [
            'user_name' => [
                'required',
                new CheckMaxLength('ユーザー名', 50),
                // new CheckUnique('ユーザー名', ['id' => $request->route('id')]),
            ],
            'password' => [
                'nullable',
                new CheckAlphaNum('パスワード'),
                new CheckMaxLength('パスワード', 20),
                new CheckMinLength('パスワード', 8),
            ],
            'jarp_type' => [
                'required_if:user_type,2',
                new CheckValueList('自再協権限', 'MstUser.jarpType', 'user_type,2', $jarpTypeDataCheck, 'mst032_edit')
            ],
            'email' => [
                'nullable',
                new CheckMailRFC(),
                new CheckMaxLength('メールアドレス', 100),
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
            // ユーザー名
            'user_name.required' => ConfigUtil::getMessage('c-001', ['ユーザー名']),
            // パスワード
            'password.required' => ConfigUtil::getMessage('c-001', ['パスワード']),
            // 自再協権限
            'jarp_type.required_if' => ConfigUtil::getMessage('c-001', ['自再協権限']),
            // メールアドレス
            'email.email' => ConfigUtil::getMessage('c-007', ['メールアドレス']),
        ];
    }
}
