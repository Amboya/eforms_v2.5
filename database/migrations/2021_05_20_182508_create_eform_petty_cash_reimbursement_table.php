<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformPettyCashReimbursementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_pt_reimbursement', function (Blueprint $table) {
            $table->id();
            $table->integer('user_unit_id');
            $table->string('user_unit_code')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->double('amount', 20, 5)->nullable();
            $table->string('reason')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('profile')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('cash_percentage')->nullable();
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
        Schema::dropIfExists('eform_pt_reimbursement');
    }
}
