<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTacColumnInTacNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tac_numbers', function (Blueprint $table) {
            $table->dropColumn('purpose');
        });

        Schema::table('tac_numbers', function (Blueprint $table) {
            $table->string('tac', 255)->change();
            $table->enum('purpose', ['reset_password', 'register'])->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tac_numbers', function (Blueprint $table) {
            $table->dropColumn('purpose');
        });

        Schema::table('tac_numbers', function (Blueprint $table) {
            $table->string('tac', 20)->change();
            $table->enum('purpose', ['password_reset', 'register'])->after('id')->index();
        });
    }
}
