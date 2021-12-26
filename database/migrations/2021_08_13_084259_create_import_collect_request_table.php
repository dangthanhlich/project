<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCollectRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_collect_request', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('request_date', 255)->nullable();
            $table->string('src_office_code', 255)->nullable();
            $table->string('src_office_name', 255)->nullable();
            $table->string('src_office_address', 255)->nullable();
            $table->string('dst_office_code', 255)->nullable();
            $table->string('dst_office_name', 255)->nullable();
            $table->string('dst_office_address', 255)->nullable();
            $table->string('deliver_report_date', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('cancel_date', 255)->nullable();
            $table->string('case_id', 255)->nullable();
            $table->string('case_no', 255)->nullable();
            $table->string('move_report_no', 255)->nullable();
            $table->string('car_no', 255)->nullable();
            $table->string('car_name', 255)->nullable();
            $table->string('equipment_qty', 255)->nullable();
            $table->string('mechanical_type', 255)->nullable();
            $table->unsignedTinyInteger('imported_type')->nullable();
            $table->unsignedBigInteger('diff_collect_request_id')->nullable();

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
        Schema::dropIfExists('import_collect_request');
    }
}
