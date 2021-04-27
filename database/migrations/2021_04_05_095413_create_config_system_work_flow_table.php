<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSystemWorkFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_system_work_flow', function (Blueprint $table) {
            $table->id();
            $table->integer('user_unit_id')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->string('user_unit_description')->nullable();
            $table->string('user_unit_superior')->nullable();
            $table->string('user_unit_bc_code')->nullable();
            $table->string('user_unit_cc_code')->nullable();
            $table->string('user_unit_status')->nullable();

            $table->string('dr_code')->nullable();
            $table->string('dr_unit')->nullable();

            $table->string('dm_code')->nullable();
            $table->string('dm_unit')->nullable();

            $table->string('hod_code')->nullable();
            $table->string('hod_unit')->nullable();

            $table->string('arm_code')->nullable();
            $table->string('arm_unit')->nullable();

            $table->string('bm_code')->nullable();
            $table->string('bm_unit')->nullable();

            $table->string('ca_code')->nullable();
            $table->string('ca_unit')->nullable();

            $table->string('ma_code')->nullable();
            $table->string('ma_unit')->nullable();

            $table->string('psa_code')->nullable();
            $table->string('psa_unit')->nullable();

            $table->string('hrm_code')->nullable();
            $table->string('hrm_unit')->nullable();

            $table->string('phro_code')->nullable();
            $table->string('phro_unit')->nullable();

            $table->string('shro_unit')->nullable();
            $table->string('shro_code')->nullable();

            $table->string('audit_code')->nullable();
            $table->string('audit_unit')->nullable();

            $table->string('expenditure_code')->nullable();
            $table->string('expenditure_unit')->nullable();

            $table->string('payroll_code')->nullable();
            $table->string('payroll_unit')->nullable();

            $table->string('security_code')->nullable();
            $table->string('security_unit')->nullable();

            $table->string('transport_code')->nullable();
            $table->string('transport_unit')->nullable();

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
        Schema::dropIfExists('config_system_work_flow');
    }
}
