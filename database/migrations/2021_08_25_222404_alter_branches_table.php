<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('branches');

        Schema::create('branches', function (Blueprint $table) {

            $table->unsignedBigInteger('main_branch_id')->index();
            $table->unsignedBigInteger('sub_branch_id')->index();
            $table->enum('status', ['publish', 'draft']);

            $table->foreign('main_branch_id')->references('id')->on('users');
            $table->foreign('sub_branch_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
