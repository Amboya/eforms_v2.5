<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalsTblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('totals_tbl', function (Blueprint $table) {
            $table->id();

            $table->string('column_one')->nullable();
            $table->string('column_one_value')->nullable();

            $table->string('column_two')->nullable();
            $table->string('column_two_value')->nullable();

            $table->string('total_one')->nullable();
            $table->integer('total_one_value')->nullable();

            $table->string('total_two')->nullable();
            $table->double('total_two_value', 20, 2)->nullable();

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
        Schema::dropIfExists('totals_tbl');
    }
}
