<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallet', function (Blueprint $table) {
            $table->unsignedBigInteger('pallet_id')->autoIncrement();
            $table->string('pallet_no', 6)->nullable();
            $table->unsignedTinyInteger('pallet_status')->nullable();
            $table->unsignedBigInteger('pallet_transport_id')->nullable();
            $table->string('sy_office_code', 12)->nullable();
            $table->dateTime('receive_complete_time')->nullable();
            $table->unsignedBigInteger('receive_user_id')->nullable();

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
        Schema::dropIfExists('pallet');
    }
}
