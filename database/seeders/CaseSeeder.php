<?php

namespace Database\Seeders;

use App\Libs\ValueUtil;
use App\Models\MstOffice;
use App\Models\MstScrapper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('case')->insert([
            'case_id'=>'case1',
            'case_no' => '123A1',
            'transport_type' => '1',
            'scrapper_office_code'=>'111a',
            'tr_office_code'=>'3213A1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('case')->insert([
            'case_id'=>'case2',
            'case_no' => '123A1',
            'transport_type' => '1',
            'scrapper_office_code'=>'222b',
            'tr_office_code'=>'3343A1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('case')->insert([
            'case_id'=>'case3',
            'case_no' => '123A1',
            'transport_type' => '1',
            'scrapper_office_code'=>'333c',
            'tr_office_code'=>'3813A1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
