<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesImportCollectRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_collect_request', function (Blueprint $table) {
            $table->foreign('diff_collect_request_id')->references('id')->on('diff_collect_request');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_collect_request', function (Blueprint $table) {
            $table->dropForeign(['diff_collect_request_id']);
        });
    }
}
