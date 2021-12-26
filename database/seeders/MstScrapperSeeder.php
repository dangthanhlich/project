<?php

namespace Database\Seeders;

use App\Libs\ValueUtil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstScrapperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_scrapper')->insert([
            'office_code' => '111a',
            'company_code' => '111A11',
            'office_name' => 'Anh Anh',
            'tr_office_code'=>'3343A1',
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);

        DB::table('mst_scrapper')->insert([
            'office_code' => '222b',
            'company_code' => '222A22',
            'office_name' => 'Hello Anh',
            'tr_office_code'=>'3343A1',
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);

        DB::table('mst_scrapper')->insert([
            'office_code' => '333c',
            'company_code' => '333A33',
            'office_name' => 'Hello Hello',
            'tr_office_code'=>'3343A1',
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);
    }
}
