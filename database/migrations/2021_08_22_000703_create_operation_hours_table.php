<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->index();
            $table->unsignedInteger('days_of_week');
            $table->boolean('day_off')->default(0);
            $table->time('start')->default('00:00:00');
            $table->time('end')->default('23:59:59');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_hours');
    }
}
