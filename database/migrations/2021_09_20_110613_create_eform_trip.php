<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformTrip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_trip', function (Blueprint $table) {
            $table->id();
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();
            $table->string('hod_code')->nullable();
            $table->string('hod_unit')->nullable();

            $table->string('code');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('destination')->nullable();
            $table->integer('config_status_id')->nullable();
            $table->integer('invited')->nullable();

            $table->string('initiator_name')->nullable();
            $table->string('initiator_staff_no')->nullable();
            $table->string('initiator_date')->nullable();

            $table->string('closed_by_name')->nullable();
            $table->string('closed_by_staff_no')->nullable();
            $table->string('closed_by_date')->nullable();

            $table->integer('created_by');
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
        Schema::dropIfExists('eform_trip');
    }
}
