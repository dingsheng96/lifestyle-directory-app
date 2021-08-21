<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('position')->comment('recruitment position');
            $table->longText('description')->comment('job description');
            $table->unsignedBigInteger('min_salary');
            $table->unsignedBigInteger('max_salary');
            $table->boolean('show_salary');
            $table->enum('status', ['publish', 'draft']);
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
        Schema::dropIfExists('careers');
    }
}
