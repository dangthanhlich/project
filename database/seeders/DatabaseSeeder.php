<?php

namespace Database\Seeders;

use App\Models\MstScrapper;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            MstUserSeeder::class,
            MstPriceSeeder::class,
            MstOfficeSeeder::class,
            MstScrapperSeeder::class,
            CaseSeeder::class,
        ]);
    }
}
