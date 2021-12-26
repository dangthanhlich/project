<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesImportReceiveCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_receive_car', function (Blueprint $table) {
            $table->foreign('diff_receive_report_id')->references('id')->on('diff_receive_report');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_receive_car', function (Blueprint $table) {
            $table->dropForeign(['diff_receive_report_id']);
        });
    }
}
