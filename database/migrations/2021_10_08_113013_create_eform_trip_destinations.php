<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformTripDestinations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_trip_destinations', function (Blueprint $table) {
            $table->id();
            $table->integer('trip_id')->nullable();
            $table->string('trip_code')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eform_trip_destinations');
    }
}
