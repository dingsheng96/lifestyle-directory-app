<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserIdFromDeviceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_settings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');

            $table->dropColumn('status');

            $table->timestamp('last_activated_at')->nullable()->after('device_os');
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

            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->foreign('user_id')->references('id')->on('users');

            $table->dropColumn('last_activated_at');

            $table->enum('status', ['active', 'inactive'])->after('device_os');
        });
    }
}
