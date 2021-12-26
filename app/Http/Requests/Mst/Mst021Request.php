<?php

namespace App\Http\Requests\Mst;

use App\Libs\ConfigUtil;
use App\Rules\{
    CheckKatakana2Byte,
    CheckMaxLength,
    CheckNumeric,
};
use Illuminate\Foundation\Http\FormRequest;

class Mst021Request extends FormRequest
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
            'office_code' => [
                'required',
                new CheckNumeric('事業所コード'),
                new CheckMaxLength('事業所コード', 12),
            ],
            'office_name' => [
                new CheckMaxLength('事業所名', 60),
            ],
            'office_name_kana' => [
                new CheckMaxLength('事業所名（カナ）', 120),
                new CheckKatakana2Byte('事業所名（カナ）'),
            ],
            'office_address_zip' => [
                new CheckMaxLength('事業所住所 郵便番号', 8),
            ],
            'office_address_pref' => [
                new CheckMaxLength('事業所住所 都道府県', 4),
            ],
            'office_address_city' => [
                new CheckMaxLength('事業所住所 市区町村', 20),
            ],
            'office_address_town' => [
                new CheckMaxLength('事業所住所 町字', 15),
            ],
            'office_address_block' => [
                new CheckMaxLength('事業所住所 番地', 20),
            ],
            'office_address_building' => [
                new CheckMaxLength('事業所住所 建物名', 31),
            ],
            'office_tel' => [
                new CheckMaxLength('電話番号', 13),
            ],
            'office_fax' => [
                new CheckMaxLength('FAX番号', 13),
            ],
            'pic_name' => [
                new CheckMaxLength('担当者名', 60),
            ],
            'pic_name_kana' => [
                new CheckMaxLength('担当者名（カナ）', 120),
            ],
            'pic_tel' => [
                new CheckMaxLength('担当者電話番号', 13),
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
            // 事業所コード
            'office_code.required' => ConfigUtil::getMessage('c-001', ['事業所コード']),
            // 'office_code.integer' => ConfigUtil::getMessage('c-004', ['事業所コード']),
        ];
    }
}
