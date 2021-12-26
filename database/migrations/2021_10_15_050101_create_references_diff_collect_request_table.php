<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesDiffCollectRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diff_collect_request', function (Blueprint $table) {
            $table->foreign('case_id')->references('case_id')->on('case');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diff_collect_request', function (Blueprint $table) {
            $table->dropForeign(['case_id']);
        });
    }
}
