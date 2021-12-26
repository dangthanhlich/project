<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesMstScrapperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mst_scrapper', function (Blueprint $table) {
            $table->foreign('tr_office_code')->references('office_code')->on('mst_office');
            $table->foreign('sy_office_code')->references('office_code')->on('mst_office');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_scrapper', function (Blueprint $table) {
            $table->dropForeign(['tr_office_code']);
            $table->dropForeign(['sy_office_code']);
        });
    }
}
