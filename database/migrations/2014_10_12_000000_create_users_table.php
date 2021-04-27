<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('staff_no')->unique();
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();

            $table->string('job_code')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->integer('user_unit_id')->nullable();
            $table->integer('user_directorate_id')->nullable();
            $table->integer('user_division_id')->nullable();
            $table->integer('user_region_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('pay_point_id')->nullable();
            $table->integer('functional_unit_id')->nullable();

            $table->string('nrc')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('con_st_code')->nullable();
            $table->string('con_wef_date')->nullable();
            $table->string('con_wet_date')->nullable();

            $table->integer('positions_id')->nullable();
            $table->integer('profile_id')->default('0');
            $table->integer('type_id')->default('0');
            $table->integer('grade_id')->default('0');

            $table->integer('total_login')->default('0');
            $table->integer('total_forms')->default('0');
            $table->integer('password_changed')->default('0');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
