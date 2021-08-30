<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsInDeviceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_settings', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->index('serial_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_settings', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id')->change();
            $table->dropIndex(['serial_no']);
        });
    }
}
