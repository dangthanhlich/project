<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecycleReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recycle_report', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('rp_office_code', 12)->nullable();
            $table->string('report_month', 6)->nullable();
            $table->float('weight_before')->nullable();
            $table->float('weight_after')->nullable();
            $table->float('recycle_rate')->nullable();
            $table->unsignedInteger('total_process_qty')->nullable();
            $table->unsignedInteger('max_process_qty')->nullable();
            $table->float('operation_rate')->nullable();
            $table->float('weight_per_piece')->nullable();

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
        Schema::dropIfExists('recycle_report');
    }
}
