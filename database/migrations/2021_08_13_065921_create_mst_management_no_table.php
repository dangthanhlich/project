<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstManagementNoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_management_no', function (Blueprint $table) {
            $table->string('management_no', 10);
            $table->string('company_code_2nd_tr', 12);

            $table->unsignedTinyInteger('del_flg')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->datetime('deleted_at')->nullable();

            $table->primary('management_no');
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
        Schema::dropIfExists('mst_management_no');
    }
}
