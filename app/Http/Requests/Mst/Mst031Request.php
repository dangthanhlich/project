<?php

namespace App\Http\Requests\Mst;

use App\Libs\ConfigUtil;
use App\Rules\{
    CheckAlphaNum,
    CheckMailRFC,
    CheckMaxLength,
    CheckMinLength,
    CheckUnique,
    CheckValueList,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Mst031Request extends FormRequest
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
        return [
            'id-login' => [
                'required',
                new CheckMaxLength('ログインID', 50),
                new CheckUnique('ログインID'),
            ],
            'id-user' => [
                'required',
                new CheckMaxLength('ユーザー名', 50),
                // new CheckUnique('ユーザー名'),
            ],
            'pass' => [
                'required',
                new CheckAlphaNum('パスワード'),
                new CheckMaxLength('パスワード', 20),
                new CheckMinLength('パスワード', 8),
            ],
            'user_type' => [
                'required',
                new CheckValueList('権限', 'MstUser.userType'),
            ],
            'jarp_type' => [
                'required_if:user_type,2',
                new CheckValueList('自再協権限', 'MstUser.jarpType', 'user_type,2', $request->all()),
            ],
            'office_manager' => [
                new CheckValueList('事業所管理者', 'MstUser.officeManager', 'user_type,3', $request->all()),
            ],
            'trader_authority' => [
                'required_if:user_type,3',
                new CheckValueList('業者権限', 'MstUser.traderAuthority', 'user_type,3', $request->all(), '', 'checkbox'),
            ],
            'office_code' => 'required_if:user_type,3',
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
            // ログインID
            'id-login.required'  => ConfigUtil::getMessage('c-001', ['ログインID']),
            // ユーザー名
            'id-user.required' => ConfigUtil::getMessage('c-001', ['ユーザー名']),
            // パスワード
            'pass.required' => ConfigUtil::getMessage('c-001', ['パスワード']),
            // 権限
            'user_type.required' => ConfigUtil::getMessage('c-001', ['権限']),
            // 自再協権限
            'jarp_type.required_if' => ConfigUtil::getMessage('c-001', ['自再協権限']),
            // 所属事業所コード
            'office_code.required_if' => ConfigUtil::getMessage('c-001', ['所属事業所コード']),
            // メールアドレス
            'email.email' => ConfigUtil::getMessage('c-007', ['メールアドレス']),
            // 業者権限
            'trader_authority.required_if' => ConfigUtil::getMessage('c-001', ['業者権限']),
        ];
    }
}
