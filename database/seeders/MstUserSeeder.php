<?php

namespace Database\Seeders;

use App\Libs\EncryptUtil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('nw'),
            'user_name' => EncryptUtil::encryptAes256('②運N (NW)'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 3,
            'tr_office_flg' => 1,
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('sy'),
            'user_name' => EncryptUtil::encryptAes256('③SY'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 3,
            'sy_office_flg' => 1,
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('sd'),
            'user_name' => EncryptUtil::encryptAes256('④二次 (SD)'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 3,
            '2nd_tr_office_flg' => 1,
            'office_code'=>'3213A1',
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('rp'),
            'user_name' => EncryptUtil::encryptAes256('⑤RP'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 3,
            'rp_office_flg' => 1,
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('ja1'),
            'user_name' => EncryptUtil::encryptAes256('⑥自再協 (JA) - 施設管理'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 2,
            'jarp_type' => 1,
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('ja2'),
            'user_name' => EncryptUtil::encryptAes256('⑥自再協 (JA) - 渉外'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 2,
            'jarp_type' => 2,
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
        DB::table('mst_user')->insert([
            'login_id' => EncryptUtil::encryptAes256('admin'),
            'user_name' => EncryptUtil::encryptAes256('⑦システム管理者 (Admin)'),
            'password' => EncryptUtil::encryptSha256('123456789'),
            'user_type' => 1,
            'del_flg' => 0,
            'invalid_flg' => 0,
        ]);
    }
}
