<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car', function (Blueprint $table) {
            $table->unsignedBigInteger('car_id')->autoIncrement();
            $table->string('case_id', 20)->nullable();
            $table->string('car_no', 50)->nullable();
            $table->unsignedTinyInteger('car_no_change_flg')->nullable();
            $table->dateTime('car_no_change_time')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->unsignedInteger('exceed_qty')->nullable();
            $table->unsignedTinyInteger('exceed_qty_disable_flg')->nullable();
            $table->unsignedInteger('equipment_qty')->nullable();
            $table->string('mechanical_type', 3)->nullable();
            $table->string('car_picture', 255)->nullable();
            $table->unsignedInteger('actual_qty')->nullable();

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
        Schema::dropIfExists('car');
    }
}
