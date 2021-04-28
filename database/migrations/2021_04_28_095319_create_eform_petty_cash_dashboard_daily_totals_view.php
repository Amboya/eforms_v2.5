<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformPettyCashDashboardDailyTotalsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE OR REPLACE VIEW eform_petty_cash_dashboard_daily_totals_view AS(
        SELECT
        claim_date, claimant_staff_no, config_status_id, count(id) as total, sum(total_payment) as amount ,
        hod_code, hod_unit, ca_code, ca_unit, hrm_code, hrm_unit, expenditure_code,  expenditure_unit, security_code, security_unit, audit_code, audit_unit,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        group by claim_date, claimant_staff_no, config_status_id, hod_code, hod_unit, ca_code, ca_unit, hrm_code, hrm_unit, expenditure_code,  expenditure_unit, security_code,
        security_unit, audit_code, audit_unit , user_unit_code, business_unit_code ,cost_center, directorate_id
      )"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW eform_petty_cash_dashboard_daily_totals_view");
    }
}
