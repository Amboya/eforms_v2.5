<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_tax', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('tax')->nullable();
            $table->string('business_unit')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('account_code')->nullable();

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
        Schema::dropIfExists('config_tax');
    }
}
