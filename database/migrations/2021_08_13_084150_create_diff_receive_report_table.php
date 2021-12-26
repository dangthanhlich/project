<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiffReceiveReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diff_receive_report', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('case_id', 20)->nullable();
            $table->dateTime('diff_time')->nullable();
            $table->unsignedTinyInteger('diff_type')->nullable();
            $table->string('case_no_ars', 7)->nullable();
            $table->string('case_no_manifest', 7)->nullable();
            $table->string('car_no_ars', 255)->nullable();
            $table->string('car_no_manifest', 255)->nullable();
            $table->unsignedInteger('car_qty_ars')->nullable();
            $table->unsignedInteger('car_qty_manifest')->nullable();
            $table->dateTime('diff_resolve_time')->nullable();

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
        Schema::dropIfExists('diff_receive_report');
    }
}
