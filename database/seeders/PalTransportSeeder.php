<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Libs\ValueUtil;

class PalTransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
               DB::table('pallet_transport')->insert([
            [
                '2nd_tr_office_code' => '3213A1',
                'deliver_complete_time' => now(),
                'car_no' => 'carno1',
                'rp_office_code' => 'ABDER',
                'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
            ],
            [
                '2nd_tr_office_code' => '3213A1',
                'deliver_complete_time' => now(),
                'car_no' => 'carno2',
                'rp_office_code' => 'ABDCLE',
                'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
            ],

        ]);
    }
}
