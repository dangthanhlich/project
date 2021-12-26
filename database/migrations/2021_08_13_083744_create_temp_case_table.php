<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_case', function (Blueprint $table) {
            $table->unsignedBigInteger('temp_case_id')->autoIncrement();
            $table->string('temp_case_no', 7)->nullable();
            $table->unsignedInteger('case_status')->nullable();
            $table->unsignedInteger('transport_type')->nullable();
            $table->string('scrapper_office_code', 12)->nullable();
            $table->string('tr_office_code', 12)->nullable();
            $table->string('sy_office_code', 12)->nullable();
            $table->dateTime('collect_complete_time')->nullable();
            $table->unsignedBigInteger('collect_user_id')->nullable();
            $table->dateTime('receive_complete_time')->nullable();
            $table->unsignedBigInteger('receive_user_id')->nullable();
            $table->string('case_picture_1', 255)->nullable();
            $table->string('case_picture_2', 255)->nullable();
            $table->string('case_picture_3', 255)->nullable();
            $table->dateTime('release_time')->nullable();

            $table->unsignedTinyInteger('del_flg')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->datetime('deleted_at')->nullable();

            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_case');
    }
}
