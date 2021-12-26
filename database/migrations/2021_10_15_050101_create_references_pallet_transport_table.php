<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesPalletTransportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallet_transport', function (Blueprint $table) {
            $table->foreign('2nd_tr_office_code')->references('office_code')->on('mst_office');
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
        Schema::table('pallet_transport', function (Blueprint $table) {
            $table->dropForeign(['2nd_tr_office_code']);
            $table->dropForeign(['rp_office_code']);
        });
    }
}
