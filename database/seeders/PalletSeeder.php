<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Libs\ValueUtil;

class PalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pallet')->insert([
            [
                'sy_office_code' => '123A33',
                'pallet_transport_id' => 1,
                'receive_complete_time' => now(),
                'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
            ],
            [
                'sy_office_code' => '12BA34',
                'pallet_transport_id' => 2,
                'receive_complete_time' => now(),
                'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
            ],
        ]);
    }
}
