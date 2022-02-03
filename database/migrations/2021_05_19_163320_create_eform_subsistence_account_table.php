<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformSubsistenceAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_subsistence_account', function (Blueprint $table) {

            $table->id();
            $table->integer('CREDITTED_ACCOUNT_ID')->nullable();
            $table->string('CREDITTED_AMOUNT')->nullable();
            $table->integer('DEBITTED_ACCOUNT_ID')->nullable();
            $table->string('DEBITTED_AMOUNT')->nullable();
            $table->integer('EFORM_SUBSISTENCE_ID')->nullable();
            $table->string('SUBSISTENCE_CODE')->nullable();
            $table->integer('STATUS_ID')->nullable();
            $table->string('ACCOUNT')->nullable();
            $table->string('COMPANY')->nullable();
            $table->string('INTRA_COMPANY')->nullable();
            $table->string('PROJECT')->nullable();
            $table->string('PEMS_PROJECT')->nullable();
            $table->string('SPARE')->nullable();
            $table->string('DESCRIPTION')->nullable();
            $table->string('USER_UNIT_CODE')->nullable();
            $table->string('COST_CENTER')->nullable();
            $table->string('CLAIMANT_STAFF_NO')->nullable();
            $table->string('CLAIMANT_NAME')->nullable();
            $table->string('BUSINESS_UNIT_CODE')->nullable();
            $table->string('VAT_RATE')->nullable();
            $table->string('ORG_ID')->nullable();
            $table->string('LINE_TYPE')->nullable();
            $table->string('ACCOUNT_TYPE')->nullable();
            $table->integer('created_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('eform_subsistence_account');
    }
}
