<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsistenceModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_subsistence', function (Blueprint $table) {
            $table->id();

            $table->string(' cost_center')->nullable();
            $table->string('business_unit_code')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->integer('user_unit_id')->nullable();
            $table->integer('pay_point_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('directorate_id')->nullable();
            $table->string('config_status_id')->nullable();
            $table->string('profile')->nullable();
            $table->string('code_superior')->nullable();
            //
            $table->string('grade')->nullable();
            $table->string('ext_no')->nullable();
            $table->string('code')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('claim_date')->nullable();
            $table->string('claimant_name')->nullable();
            $table->string('claimant_staff_no')->nullable();
            $table->string('station')->nullable();
            $table->string('section')->nullable();
            //
            $table->string('absc_absent_from')->nullable();
            $table->string('absc_absent_to')->nullable();
            $table->string('absc_visited_place_reason')->nullable();
            $table->string('absc_allowance_per_night')->nullable();
            $table->string('absc_amount')->nullable();
            //
            $table->string('trex_total_attached_claim')->nullable();
            $table->string('trex_total_claim_amount')->nullable();
            $table->string('trex_deduct_advance')->nullable();
            $table->string('trex_net_amount_paid')->nullable();
            //
            $table->string('allocation_code')->nullable();
            $table->string('total_amount')->nullable();
            //
            $table->string('authorised_by')->nullable();
            $table->string('authorised_staff_no')->nullable();
            $table->string('authorised_date')->nullable();
            //
            $table->string('station_manager')->nullable();
            $table->string('station_manager_staff_no')->nullable();
            $table->string('station_manager_date')->nullable();
            //
            $table->string('chief_accountant')->nullable();
            $table->string('chief_accountant_staff_no')->nullable();
            $table->string('chief_accountant_date')->nullable();
            //
            $table->string('hr_office')->nullable();
            $table->string('hr_office_staff_no')->nullable();
            $table->string('hr_date')->nullable();
            //
            $table->string('audit_name')->nullable();
            $table->string('audit_staff_no')->nullable();
            $table->string('audit_date')->nullable();

            $table->string('expenditure_office')->nullable();
            $table->string('expenditure_office_staff_no')->nullable();
            $table->string('expenditure_date')->nullable();
            //
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
        Schema::dropIfExists('eform_subsistence');
    }
}
