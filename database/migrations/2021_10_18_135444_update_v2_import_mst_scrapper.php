<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateV2ImportMstScrapper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_mst_scrapper', function (Blueprint $table) {
            for ($x = 1; $x <= 13; $x++) {
                if ($x === 1) {
                    $table->string('scrapper_0'.$x, 255)->nullable()->after('airbag_sy_05');
                } else if ($x > 1 && $x <= 9) {
                    $table->string('scrapper_0'.$x, 255)->nullable()->after('scrapper_0'.$x-1);
                } else if ($x === 10) {
                    $table->string('scrapper_'.$x, 255)->nullable()->after('scrapper_0'.$x-1);
                } else {
                    $table->string('scrapper_'.$x, 255)->nullable()->after('scrapper_'.$x-1);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
