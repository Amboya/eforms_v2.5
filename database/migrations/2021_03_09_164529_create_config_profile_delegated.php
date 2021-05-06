<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigProfileDelegated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_profile_delegated', function (Blueprint $table) {
            $table->id();
            $table->integer('eform_id');
            $table->string('eform_code');
            $table->integer('delegated_to')->nullable();
            $table->integer('delegated_user_unit')->nullable();
            $table->string('delegation_end')->nullable();
            $table->string('delegated_job_code')->nullable();
            $table->string('delegated_profile')->nullable();
            $table->integer('config_status_id')->nullable();
            $table->string('reason');
            $table->string('created_by');
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
        Schema::dropIfExists('config_profile_delegated');
    }
}
