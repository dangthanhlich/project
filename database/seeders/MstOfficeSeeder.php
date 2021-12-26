<?php

namespace Database\Seeders;

use App\Libs\ValueUtil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_office')->insert([
            'office_code' => '3213A1',
            'company_code' => '123A33',
            'office_name' => 'Gia Gia',
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);

        DB::table('mst_office')->insert([
            'office_code' => '3343A1',
            'company_code' => '103A33',
            'office_name' => 'Thanh Thanh',
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);

        DB::table('mst_office')->insert([
            'office_code' => '3813A1',
            'company_code' => '223A33',
            'office_name' => 'Gia Thanh',
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);
    }
}
