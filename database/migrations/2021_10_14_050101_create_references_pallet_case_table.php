<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesPalletCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallet_case', function (Blueprint $table) {
            $table->foreign('case_id')->references('case_id')->on('case');
            $table->foreign('pallet_id')->references('pallet_id')->on('pallet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pallet_case', function (Blueprint $table) {
            $table->dropForeign(['pallet_id']);
            $table->dropForeign(['case_id']);
        });
    }
}
