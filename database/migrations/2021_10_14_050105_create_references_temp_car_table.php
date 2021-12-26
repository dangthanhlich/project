<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesTempCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_car', function (Blueprint $table) {
            $table->foreign('temp_case_id')->references('temp_case_id')->on('temp_case');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_car', function (Blueprint $table) {
            $table->dropForeign(['temp_case_id']);
        });
    }
}
