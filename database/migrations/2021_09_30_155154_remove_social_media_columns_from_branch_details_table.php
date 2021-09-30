<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSocialMediaColumnsFromBranchDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_details', function (Blueprint $table) {

            $table->dropColumn('website');
            $table->dropColumn('facebook');
            $table->dropColumn('whatsapp');
            $table->dropColumn('instagram');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_details', function (Blueprint $table) {
            $table->string('website')->nullable()->after('services');
            $table->string('facebook')->nullable()->after('services');
            $table->string('whatsapp')->nullable()->after('services');
            $table->string('instagram')->nullable()->after('services');
        });
    }
}
