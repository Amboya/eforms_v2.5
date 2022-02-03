<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecoveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_subsistence_recoveries', function (Blueprint $table) {
            $table->id();

            $table->string('eform_subsistence_id')->nullable();
            $table->string('eform_subsistence_code')->nullable();
            $table->string('actual_day')->nullable();
            $table->string('days_to')->nullable();
            $table->string('per_night')->nullable();
            $table->double('amount')->nullable();
            $table->double('recovered')->nullable();
            $table->string('status_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eform_subsistence_recoveries');
    }
}
