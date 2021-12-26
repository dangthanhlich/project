<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case', function (Blueprint $table) {
            $table->string('case_id', 20);
            $table->string('case_no', 7)->nullable();
            $table->string('case_no_old', 7)->nullable();
            $table->unsignedTinyInteger('case_status')->nullable();
            $table->unsignedTinyInteger('transport_type')->nullable();
            $table->string('scrapper_office_code', 12)->nullable();
            $table->string('tr_office_code', 12)->nullable();
            $table->string('sy_office_code', 12)->nullable();

            // auto_increment（開始：100,000,001）
            $table->unsignedBigInteger('slip_no')->nullable();

            $table->dateTime('collect_complete_time')->nullable();
            $table->unsignedBigInteger('collect_user_id')->nullable();
            $table->dateTime('receive_complete_time')->nullable();
            $table->unsignedBigInteger('receive_user_id')->nullable();
            $table->dateTime('inspect_complete_time')->nullable();
            $table->unsignedBigInteger('inspect_user_id')->nullable();
            $table->dateTime('recheck_time')->nullable();
            $table->unsignedBigInteger('recheck_user_id')->nullable();
            $table->dateTime('report_check_time')->nullable();
            $table->unsignedBigInteger('report_check_user_id')->nullable();
            $table->dateTime('rp_inspect_complete_time')->nullable();
            $table->unsignedBigInteger('rp_inspect_user_id')->nullable();
            $table->unsignedBigInteger('collect_request_id')->nullable();
            $table->dateTime('collect_request_link_time')->nullable();
            $table->date('collect_request_time')->nullable();
            $table->dateTime('deliver_report_time')->nullable();
            $table->dateTime('receive_report_time')->nullable();
            $table->string('case_picture_1', 255)->nullable();
            $table->string('case_picture_2', 255)->nullable();
            $table->string('case_picture_3', 255)->nullable();
            $table->string('case_picture_4', 255)->nullable();
            $table->unsignedInteger('actual_qty_sy')->nullable();
            $table->unsignedInteger('actual_qty_rp')->nullable();
            $table->unsignedTinyInteger('case_no_change_flg')->nullable();
            $table->dateTime('case_no_change_time')->nullable();
            $table->unsignedBigInteger('transport_fee')->nullable();
            $table->string('collect_failure_reason', 255)->nullable();
            $table->dateTime('collect_failure_time')->nullable();
            $table->string('return_reason', 255)->nullable();
            $table->dateTime('return_time')->nullable();
            $table->string('temp_case_id')->nullable();
            $table->unsignedTinyInteger('exceed_qty_flg')->nullable();
            $table->unsignedTinyInteger('inspect_stop_flg')->nullable();
            $table->unsignedTinyInteger('receive_report_diff_flg')->nullable();
            $table->unsignedTinyInteger('collect_request_cancel_flg')->nullable();
            $table->string('collect_request_cancel_case_id', 20)->nullable();

            $table->unsignedTinyInteger('del_flg')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->datetime('deleted_at')->nullable();

            $table->primary('case_id');
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
        Schema::dropIfExists('case');
    }
}
