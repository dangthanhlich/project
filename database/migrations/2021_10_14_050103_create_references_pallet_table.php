<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesPalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallet', function (Blueprint $table) {
            $table->foreign('pallet_transport_id')->references('pallet_transport_id')->on('pallet_transport');
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
        Schema::table('pallet', function (Blueprint $table) {
            $table->dropForeign(['pallet_transport_id']);
            $table->dropForeign(['sy_office_code']);
        });
    }
}
