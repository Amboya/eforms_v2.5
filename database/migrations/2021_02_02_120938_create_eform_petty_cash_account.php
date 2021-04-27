<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformPettyCashAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_petty_cash_account', function (Blueprint $table) {
            $table->id();

            $table->integer('creditted_account_id')->nullable();
            $table->string('creditted_amount')->nullable();
            $table->integer('debitted_account_id')->nullable();
            $table->string('debitted_amount')->nullable();
            $table->integer('eform_petty_cash_id')->nullable();

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
        Schema::dropIfExists('eform_petty_cash_account');
    }
}
