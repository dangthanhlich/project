<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportReceiveCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_receive_car', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('start_date', 255)->nullable();
            $table->string('end_date', 255)->nullable();
            $table->string('move_report_no', 255)->nullable();
            $table->string('car_no', 255)->nullable();
            $table->string('car_name', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->string('brand_code', 255)->nullable();
            $table->string('brand_name', 255)->nullable();
            $table->string('debtor_code', 255)->nullable();
            $table->string('debtor_name', 255)->nullable();
            $table->string('inflator_qty_1', 255)->nullable();
            $table->string('inflator_qty_2', 255)->nullable();
            $table->string('inflator_qty_3', 255)->nullable();
            $table->string('inflator_qty_4', 255)->nullable();
            $table->string('pretensioner_qty', 255)->nullable();
            for ($i = 1; $i <= 10; $i++) {
                $table->string('other_part_name_'.$i, 255)->nullable();
                $table->string('other_part_qty_'.$i, 255)->nullable();
            }
            $table->string('bulk_operation_type', 255)->nullable();
            $table->string('mechanical_type', 255)->nullable();
            $table->string('other_type_1', 255)->nullable();
            $table->string('other_type_2', 255)->nullable();
            $table->string('summary_exclude_flg', 255)->nullable();
            $table->string('collect_payment_pattern', 255)->nullable();
            $table->string('operation_payment_pattern', 255)->nullable();
            $table->string('equipment_qty', 255)->nullable();
            $table->string('receive_report_time', 255)->nullable();
            $table->string('case_id', 255)->nullable();
            $table->string('collect_flg', 255)->nullable();
            $table->string('actual_collect_qty', 255)->nullable();
            $table->string('collect_qty', 255)->nullable();
            $table->string('exceed_qty', 255)->nullable();
            $table->string('operation_implement_flg', 255)->nullable();
            $table->string('operation_qty', 255)->nullable();
            $table->unsignedTinyInteger('imported_type')->nullable();
            $table->unsignedBigInteger('diff_receive_report_id')->nullable();

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
        Schema::dropIfExists('import_receive_car');
    }
}
