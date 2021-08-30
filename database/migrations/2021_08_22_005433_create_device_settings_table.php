<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('model')->comment('device model');
            $table->string('serial_no');
            $table->string('manufacturer');
            $table->enum('status', ['active', 'inactive']);
            $table->string('push_messaging_provider');
            $table->string('push_messaging_token');
            $table->boolean('enable_push_messaging')->default(1);
            $table->boolean('enable_notification_sound')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_settings');
    }
}
