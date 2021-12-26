<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletTransportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallet_transport', function (Blueprint $table) {
            $table->unsignedBigInteger('pallet_transport_id')->autoIncrement();
            $table->string('car_no', 20)->nullable();
            $table->string('car_no_picture_1', 255)->nullable();
            $table->string('car_no_picture_2', 255)->nullable();
            $table->dateTime('deliver_complete_time')->nullable();
            $table->unsignedBigInteger('deliver_user_id')->nullable();
            $table->string('2nd_tr_office_code', 12)->nullable();
            $table->string('rp_office_code', 12)->nullable();

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
        Schema::dropIfExists('pallet_transport');
    }
}
