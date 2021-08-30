<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTacNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tac_numbers', function (Blueprint $table) {
            $table->id();
            $table->enum('purpose', ['password_reset', 'register']);
            $table->string('mobile_no')->index();
            $table->string('tac', 20)->index();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tac_numbers');
    }
}
