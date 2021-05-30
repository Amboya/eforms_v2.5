<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformPettyCashFloatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_petty_cash_float', function (Blueprint $table) {
            $table->id();
            $table->integer('user_unit_id');
            $table->string('user_unit_code')->nullable();
            $table->double('float',20,5)->nullable();
            $table->double('utilised',20,5)->nullable();
            $table->double('cash',20,5)->nullable();
            $table->integer('percentage')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('created_by_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eform_petty_cash_float');
    }
}
