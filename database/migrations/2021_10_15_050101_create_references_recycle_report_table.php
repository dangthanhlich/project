<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesRecycleReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recycle_report', function (Blueprint $table) {
            $table->foreign('rp_office_code')->references('office_code')->on('mst_office');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recycle_report', function (Blueprint $table) {
            $table->dropForeign(['rp_office_code']);
        });
    }
}
