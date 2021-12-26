<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTempCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('temp_case', 'case_status')) {
            Schema::table('temp_case', function (Blueprint $table) {
                $table->dropColumn('case_status');
            });
        }
        if (Schema::hasColumn('temp_case', 'transport_type')) {
            Schema::table('temp_case', function (Blueprint $table) {
                $table->dropColumn('transport_type');
            });
        }
        Schema::table('temp_case', function (Blueprint $table) {
            $table->unsignedTinyInteger('case_status')->nullable()->after('temp_case_no');
            $table->unsignedTinyInteger('transport_type')->nullable()->after('case_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('temp_case', 'case_status')) {
            Schema::table('temp_case', function (Blueprint $table) {
                $table->dropColumn('case_status');
            });
        }
        if (Schema::hasColumn('temp_case', 'transport_type')) {
            Schema::table('temp_case', function (Blueprint $table) {
                $table->dropColumn('transport_type');
            });
        }
    }
}
