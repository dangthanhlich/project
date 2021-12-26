<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_office', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('office_code', 12)->nullable();
            $table->string('company_code', 12)->nullable();
            $table->string('office_name', 60)->nullable();
            $table->string('office_name_kana', 120)->nullable();
            $table->string('office_address_zip', 8)->nullable();
            $table->string('office_address_pref', 4)->nullable();
            $table->string('office_address_city', 20)->nullable();
            $table->string('office_address_town', 15)->nullable();
            $table->string('office_address_block', 20)->nullable();
            $table->string('office_address_building', 31)->nullable();
            $table->string('office_address_search', 90)->nullable();
            $table->string('office_tel', 13)->nullable();
            $table->string('office_fax', 13)->nullable();
            $table->string('pic_name', 60)->nullable();
            $table->string('pic_name_kana', 120)->nullable();
            $table->string('pic_tel', 13)->nullable();
            $table->unsignedTinyInteger('tr_office_flg')->nullable();
            $table->unsignedTinyInteger('sy_office_flg')->nullable();
            $table->string('2nd_tr_office_code', 12)->nullable();
            $table->string('rp_office_code', 12)->nullable();
            $table->unsignedTinyInteger('2nd_tr_office_flg')->nullable();
            $table->unsignedTinyInteger('rp_office_flg')->nullable();
            $table->string('record_type', 3)->nullable();
            $table->date('record_effective_date')->nullable();
            $table->unsignedTinyInteger('other_sy_bring_flg')->nullable();

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
        Schema::dropIfExists('mst_office');
    }
}
