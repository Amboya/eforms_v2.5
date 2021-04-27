<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('other');
            $table->string('description');
            $table->integer('status');
            $table->integer('status_next');
            $table->integer('status_failed');
            $table->string('html');
            $table->integer('percentage');
            $table->integer('eform_id');
            $table->string('eform_code');
            $table->string('created_by');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->integer('previous_status_id');
            $table->integer('next_status_id');
            $table->integer('failed_status_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_status');
    }
}
