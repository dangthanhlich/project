<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesPlanCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan_case', function (Blueprint $table) {
            $table->foreign('office_code_to')->references('office_code')->on('mst_office');
            $table->foreign('office_code_from')->references('office_code')->on('mst_scrapper');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_case', function (Blueprint $table) {
            $table->dropForeign(['office_code_to']);
            $table->dropForeign(['office_code_from']);
        });
    }
}
