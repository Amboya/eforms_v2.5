<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformPettyCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_petty_cash', function (Blueprint $table) {
            $table->id();

            $table->string('cost_center')->nullable();
            $table->string('business_unit_code')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->integer('user_unit_id')->nullable();
            $table->integer('pay_point_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('directorate_id')->nullable();
            $table->integer('projects_id')->nullable();

            $table->string('total_payment')->nullable();
            $table->string('change')->nullable();
            $table->string('code');
            $table->string('ref_no')->nullable();

            $table->string('profile')->nullable();
            $table->string('code_superior')->nullable();
            $table->integer('config_status_id')->nullable();

            $table->string('hod_code')->nullable();
            $table->string('hod_unit')->nullable();
            $table->string('hrm_code')->nullable();
            $table->string('hrm_unit')->nullable();
            $table->string('ca_code')->nullable();
            $table->string('ca_unit')->nullable();
            $table->string('expenditure_code')->nullable();
            $table->string('expenditure_unit')->nullable();
            $table->string('security_code')->nullable();
            $table->string('security_unit')->nullable();

            $table->string('claimant_name')->nullable();
            $table->string('claimant_staff_no')->nullable();
            $table->string('claim_date')->nullable();

            $table->string('authorised_by')->nullable();
            $table->string('authorised_staff_no')->nullable();
            $table->string('authorised_date')->nullable();

            $table->string('station_manager')->nullable();
            $table->string('station_manager_staff_no')->nullable();
            $table->string('station_manager_date')->nullable();

            $table->string('accountant')->nullable();
            $table->string('accountant_staff_no')->nullable();
            $table->string('accountant_date')->nullable();

            $table->string('expenditure_office')->nullable();
            $table->string('expenditure_office_staff_no')->nullable();
            $table->string('expenditure_date')->nullable();

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
        Schema::dropIfExists('eform_petty_cash');
    }
}
