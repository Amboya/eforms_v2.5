<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformKilometerAllowance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_kilometer_allowance', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('destination')->nullable();
            $table->string('purpose_of_visit')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('engine_capacity')->nullable();
            $table->string('fuel_type')->nullable();

            $table->double('kilometers', 10, 2)->nullable();
            $table->double('pump_price', 10, 2)->nullable();
            $table->double('amount', 10, 2)->nullable();
            $table->string('staff_name')->nullable();
            $table->string('staff_no')->nullable();
            $table->string('claim_date')->nullable();
            $table->integer('config_status_id')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->string('cost_centre')->nullable();
            $table->string('business_code')->nullable();

            $table->string('authorised_by')->nullable();
            $table->string('authorised_staff_no')->nullable();
            $table->string('authorised_date')->nullable();

            $table->string('station_manager')->nullable();
            $table->string('station_manager_staff_no')->nullable();
            $table->string('station_manager_date')->nullable();

            $table->string('hrm_manager')->nullable();
            $table->string('hrm_manager_staff_no')->nullable();
            $table->string('hrm_manager_date')->nullable();

            $table->string('accountant')->nullable();
            $table->string('accountant_staff_no')->nullable();
            $table->string('accountant_date')->nullable();

            $table->string('expenditure_office')->nullable();
            $table->string('expenditure_office_staff_no')->nullable();
            $table->string('expenditure_date')->nullable();

            $table->string('audit_office')->nullable();
            $table->string('audit_staff_no')->nullable();
            $table->string('audit_date')->nullable();

            $table->string('security_office')->nullable();
            $table->string('security_staff_no')->nullable();
            $table->string('security_date')->nullable();

            $table->string('hod_code')->nullable();
            $table->string('hod_unit')->nullable();

            $table->string('dm_code')->nullable();
            $table->string('dm_unit')->nullable();

            $table->string('hrm_code')->nullable();
            $table->string('hrm_unit')->nullable();

            $table->string('ca_code')->nullable();
            $table->string('ca_unit')->nullable();

            $table->string('audit_code')->nullable();
            $table->string('audit_unit')->nullable();

            $table->string('expenditure_code')->nullable();
            $table->string('expenditure_unit')->nullable();

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
        Schema::dropIfExists('eform_kilometer_allowance');
    }
}
