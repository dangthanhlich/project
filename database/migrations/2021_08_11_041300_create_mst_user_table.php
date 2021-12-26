<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_user', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('login_id', 100)->nullable();
            $table->string('user_name', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->unsignedTinyInteger('user_type')->nullable();
            $table->unsignedTinyInteger('jarp_type')->nullable();
            $table->string('office_code', 12)->nullable();
            $table->unsignedTinyInteger('tr_office_flg')->nullable();
            $table->unsignedTinyInteger('sy_office_flg')->nullable();
            $table->unsignedTinyInteger('2nd_tr_office_flg')->nullable();
            $table->unsignedTinyInteger('rp_office_flg')->nullable();
            $table->unsignedTinyInteger('office_admin_flg')->nullable();
            $table->string('email', 255)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->unsignedTinyInteger('invalid_flg')->nullable();
            $table->string('access_token', 255)->nullable();
            $table->dateTime('access_token_expire')->nullable();
            $table->string('refresh_token', 255)->nullable();
            $table->dateTime('refresh_token_expire')->nullable();

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
        //
    }
}
