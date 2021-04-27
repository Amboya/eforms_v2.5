<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTotals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_totals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->nullable('0');
            $table->string('color');
            $table->string('icon');
            $table->string('url');
            $table->integer('eform_id');
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
        Schema::dropIfExists('config_totals');
    }
}
