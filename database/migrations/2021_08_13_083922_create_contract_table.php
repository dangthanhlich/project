<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('case_id', 20)->nullable();
            $table->unsignedBigInteger('temp_case_id')->nullable();
            $table->string('management_no', 27)->nullable();
            $table->dateTime('management_no_print_time')->nullable();
            $table->string('sign_scrapper', 255)->nullable();
            $table->string('sign_tr_1', 255)->nullable();
            $table->string('sign_tr_2', 255)->nullable();
            $table->string('sign_sy', 255)->nullable();
            $table->string('contract_pdf', 255)->nullable();
            $table->dateTime('contract_date')->nullable();
            $table->string('contract_office_name_1', 60)->nullable();
            $table->string('contract_office_address_1', 90)->nullable();
            $table->string('contract_office_name_2', 60)->nullable();
            $table->string('contract_office_address_2', 90)->nullable();
            $table->string('contract_office_name_3', 60)->nullable();
            $table->string('contract_office_address_3', 90)->nullable();
            $table->string('contract_type', 100)->nullable();
            $table->string('contract_qty', 100)->nullable();
            $table->string('contract_price', 100)->nullable();
            $table->string('contract_scope', 100)->nullable();
            $table->string('contract_period',100)->nullable();
            $table->string('contract_case_no', 7)->nullable();

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
        Schema::dropIfExists('contract');
    }
}
