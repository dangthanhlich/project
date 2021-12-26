<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_price', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedTinyInteger('price_type')->nullable();
            $table->string('region_code', 12)->nullable();
            $table->string('sy_office_code', 12)->nullable();
            $table->date('effective_start_date')->nullable();
            $table->date('effective_end_date')->nullable();
            $table->unsignedBigInteger('unit_price')->nullable();

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
        Schema::dropIfExists('mst_price');
    }
}
