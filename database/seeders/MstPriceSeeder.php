<?php

namespace Database\Seeders;

use App\Libs\ValueUtil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 元払着払区分: 元払
        DB::table('mst_price')->insert([
            'price_type' => ValueUtil::constToValue('MstPrice.priceType.ADVANCE_PAYMENT'),
            'region_code' => 12,
            'sy_office_code' => '3213A1',
            'effective_start_date' => '2021-08-24',
            'effective_end_date' => '2021-09-12',
            'unit_price' => 1,
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);

        // 元払着払区分: 着払
        DB::table('mst_price')->insert([
            'price_type' => ValueUtil::constToValue('MstPrice.priceType.PAYMENT'),
            'region_code' => 10,
            'sy_office_code' => '3343A1',
            'effective_start_date' => '2021-08-12',
            'effective_end_date' => '2021-09-15',
            'unit_price' => 5,
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);

        // 元払着払区分: なし
        DB::table('mst_price')->insert([
            'price_type' => ValueUtil::constToValue('MstPrice.priceType.NONE'),
            'region_code' => 15,
            'sy_office_code' => '3813A1',
            'effective_start_date' => '2021-08-23',
            'effective_end_date' => '2021-09-09',
            'unit_price' => 3,
            'del_flg' => ValueUtil::constToValue('Common.delFlg.NOT_DELETE'),
        ]);
    }
}
