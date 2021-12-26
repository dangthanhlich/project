<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportMstScrapperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_mst_scrapper', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('valid_date', 255)->nullable();
            for ($x = 1; $x <= 24; $x++) {
                if ($x <= 9) {
                    $table->text('company_0'.$x)->nullable();
                } else {
                    $table->text('company_'.$x)->nullable();
                }
            }
            for ($x = 1; $x <= 24; $x++) {
                if ($x <= 9) {
                    $table->text('office_0'.$x)->nullable();
                } else {
                    $table->text('office_'.$x)->nullable();
                }
            }
            for ($x = 1; $x <= 46; $x++) {
                if ($x <= 9) {
                    $table->text('system_0'.$x)->nullable();
                } else {
                    $table->text('system_'.$x)->nullable();
                }
            }
            for ($x = 1; $x <= 5; $x++) {
                $table->text('airbag_sy_0'.$x)->nullable();
            }

            // for ($x = 1; $x <= 12; $x++) {
            //     if ($x <= 9) {
            //         $table->string('destination_0'.$x, 255)->nullable();
            //     } else {
            //         $table->string('destination_'.$x, 255)->nullable();
            //     }
            // }
            // for ($x = 1; $x <= 24; $x++) {
            //     if ($x <= 9) {
            //         $table->string('recycle_0'.$x, 255)->nullable();
            //     } else {
            //         $table->string('recycle_'.$x, 255)->nullable();
            //     }
            // }
            // for ($x = 1; $x <= 5; $x++) {
            //     $table->string('freon_unique_0'.$x, 255)->nullable();
            // }
            // for ($x = 1; $x <= 5; $x++) {
            //     $table->string('direct_debit_0'.$x, 255)->nullable();
            // }
            // for ($x = 1; $x <= 8; $x++) {
            //     $table->string('freon_sy_0'.$x, 255)->nullable();
            // }
            // for ($x = 1; $x <= 4; $x++) {
            //     $table->string('crush_0'.$x, 255)->nullable();
            // }
            // for ($x = 1; $x <= 6; $x++) {
            //     $table->string('contractor_0'.$x, 255)->nullable();
            // }
            // for ($x = 1; $x <= 10; $x++) {
            //     if ($x <= 9) {
            //         $table->string('freon_0'.$x, 255)->nullable();
            //     } else {
            //         $table->string('freon_'.$x, 255)->nullable();
            //     }
            // }
            // for ($x = 1; $x <= 13; $x++) {
            //     if ($x <= 9) {
            //         $table->string('scrapper_0'.$x, 255)->nullable();
            //     } else {
            //         $table->string('scrapper_'.$x, 255)->nullable();
            //     }
            // }
            // for ($x = 1; $x <= 19; $x++) {
            //     if ($x <= 9) {
            //         $table->string('account_0'.$x, 255)->nullable();
            //     } else {
            //         $table->string('account_0'.$x, 255)->nullable();
            //     }
            // }
            // for ($x = 1; $x <= 20; $x++) {
            //     if ($x <= 9) {
            //         $table->string('certification_0'.$x, 255)->nullable();
            //     } else {
            //         $table->string('certification_'.$x, 255)->nullable();
            //     }
            // }
            // for ($x = 43; $x <= 46; $x++) {
            //     $table->string('system_'.$x, 255)->nullable();
            // }
            $table->unsignedTinyInteger('imported_type')->nullable();

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
        Schema::dropIfExists('import_mst_scrapper');
    }
}
