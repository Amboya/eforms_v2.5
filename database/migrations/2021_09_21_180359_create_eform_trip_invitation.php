<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformTripInvitation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_trip_invitation', function (Blueprint $table) {
            $table->id();
            $table->string('man_no')->nullable();
            $table->string('trip_code')->nullable();
            $table->string('date_from')->nullable();
            $table->string('user_unit')->nullable();
            $table->string('date_to')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('subsistence_id')->nullable();

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
        Schema::dropIfExists('eform_trip_invitation');
    }
}
