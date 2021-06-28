<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformPurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_no')->nullable();
            $table->string('reason_for_reinstatement')->nullable();
            $table->string('purchase_order_value')->nullable();

            $table->string('employee_staff_name')->nullable();
            $table->string('employee_job_title')->nullable();
            $table->string('employee_staff_no')->nullable();
            $table->string('employee_claim_date')->nullable();

            $table->string('staff_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('staff_no')->nullable();
            $table->string('claim_date')->nullable();

            $table->string('checker_name')->nullable();
            $table->string('checker_job_title')->nullable();
            $table->string('checker_staff_no')->nullable();
            $table->string('checker_date')->nullable();

            $table->string('approver_name')->nullable();
            $table->string('approver_job_title')->nullable();
            $table->string('approver_staff_no')->nullable();
            $table->string('approver_date')->nullable();
            $table->string('code');

            $table->string('reinstater_name')->nullable();
            $table->string('reinstater_job_title')->nullable();
            $table->string('reinstater_staff_no')->nullable();
            $table->string('reinstater_date')->nullable();

            $table->string('profile')->nullable();
            $table->string('code_superior')->nullable();
            $table->integer('config_status_id')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->string('cost_centre')->nullable();
            $table->string('business_code')->nullable();


            $table->string('ch_code')->nullable();
            $table->string('ch_unit')->nullable();
            $table->string('hod_code')->nullable();
            $table->string('hod_unit')->nullable();
            $table->string('re_code')->nullable();
            $table->string('re_unit')->nullable();

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
        Schema::dropIfExists('eform_purchase_orders');
    }
}
