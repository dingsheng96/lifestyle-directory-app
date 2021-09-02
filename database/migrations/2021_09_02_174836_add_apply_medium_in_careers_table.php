<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplyMediumInCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {

            $table->string('website')->nullable()->after('status');
            $table->string('whatsapp')->nullable()->after('status');
            $table->string('email')->nullable()->after('status');
            $table->string('contact_no')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn('contact_no');
            $table->dropColumn('email');
            $table->dropColumn('whatsapp');
            $table->dropColumn('website');
        });
    }
}
