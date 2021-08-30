<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('addresses')) {
            return;
        }

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('addressable');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('postcode', 45);
            $table->unsignedBigInteger('city_id')->index();
            $table->double('latitude', 15, 12)->default(0);
            $table->double('longitude', 15, 12)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
