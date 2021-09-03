<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicationStatusInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('application_status');
            $table->dropColumn('listing_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'merchant', 'member', 'guest'])->default('guest')->after('id');
            $table->enum('application_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            $table->enum('listing_status', ['publish', 'draft'])->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('application_status');
            $table->dropColumn('listing_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'merchant', 'branch', 'member', 'guest'])->after('mobile_no');
            $table->enum('application_status', ['pending', 'approved', 'rejected'])->after('status');
            $table->enum('listing_status', ['publish', 'draft'])->after('status');
        });
    }
}
