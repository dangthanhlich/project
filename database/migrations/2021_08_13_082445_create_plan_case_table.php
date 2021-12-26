<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_case', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('office_code_to', 12)->nullable();
            $table->string('office_code_from', 12)->nullable();
            $table->unsignedTinyInteger('transport_type')->nullable();
            $table->dateTime('collect_plan_date')->nullable();
            $table->dateTime('receive_plan_date')->nullable();
            $table->string('collect_plan_memo', 255)->nullable();
            $table->string('receive_plan_memo', 255)->nullable();
            $table->unsignedInteger('case_qty')->nullable();
            $table->unsignedInteger('empty_case_qty')->nullable();
            $table->unsignedInteger('bag_qty')->nullable();
            $table->unsignedTinyInteger('plan_date_adjusted_flg')->nullable();

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
        Schema::dropIfExists('plan_case');
    }
}
