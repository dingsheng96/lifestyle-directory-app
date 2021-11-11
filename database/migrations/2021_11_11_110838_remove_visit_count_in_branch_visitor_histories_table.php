<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveVisitCountInBranchVisitorHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_visitor_histories', function (Blueprint $table) {

            $table->dropColumn('visit_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_visitor_histories', function (Blueprint $table) {

            $table->unsignedInteger('visit_count')->after('visitor_id');
        });
    }
}
