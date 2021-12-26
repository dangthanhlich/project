<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportReceiveCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_receive_case', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('start_date', 255)->nullable();
            $table->string('end_date', 255)->nullable();
            $table->string('case_id', 255)->nullable();
            $table->string('case_no', 255)->nullable();
            $table->string('receive_report_time', 255)->nullable();
            $table->string('scrapper_office_code', 255)->nullable();
            $table->string('scrapper_office_name', 255)->nullable();
            $table->string('sy_office_code', 255)->nullable();
            $table->string('sy_office_name', 255)->nullable();
            $table->string('transport_type', 255)->nullable();
            $table->string('2nd_transport_type', 255)->nullable();
            $table->string('slip_no', 255)->nullable();
            $table->unsignedTinyInteger('imported_type')->nullable();

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
        Schema::dropIfExists('import_receive_case');
    }
}
