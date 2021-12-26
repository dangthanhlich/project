<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Libs\ValueUtil;

class PalCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pallet_case')->insert([
            [
                'case_id' => 'case1',
                'pallet_id' => 1,
                'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
            ],
            [
                'case_id' => 'case2',
                'pallet_id' => 1,
                'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
            ],
        ]);
    }
}
