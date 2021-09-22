<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformTripMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_trip_members', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('man_no')->nullable();
            $table->string('name')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->string('trip_code')->nullable();
            $table->integer('trip_id')->nullable();
            $table->string('subsistence_code')->nullable();
            $table->integer('subsistence_id')->nullable();
            $table->string('destination')->nullable();

            $table->string('days_claimed');
            $table->string('m_v_number')->nullable();
            $table->string('date_arrived')->nullable();
            $table->string('date_left')->nullable();
            $table->integer('config_status_id')->nullable();

            $table->string('approved_by_name')->nullable();
            $table->string('approved_by_staff_no')->nullable();
            $table->string('approved_by_date')->nullable();

            $table->string('hrm_name')->nullable();
            $table->string('hrm_staff_no')->nullable();
            $table->string('hrm_date')->nullable();

            $table->string('authorised_by_name')->nullable();
            $table->string('authorised_by_staff_no')->nullable();
            $table->string('authorised_by_date')->nullable();

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
        Schema::dropIfExists('eform_trip_members');
    }
}
