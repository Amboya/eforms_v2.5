<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsistenceTotalsViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE OR REPLACE VIEW subsistence_totals_view AS(
        SELECT
            absc_absent_from, absc_absent_to,cost_center,business_unit_code, user_unit_code,pay_point_id,location_id,division_id,region_id,directorate_id,config_status_id,section,allocation_code,claimant_staff_no,created_at,absc_visited_place,claimant_unit_code,claimant_name,code, trip_id,
            (absc_absent_to - add_months(absc_absent_from,trunc(months_between( absc_absent_to,absc_absent_from)))) as days ,
            absc_allowance_per_night,trex_total_attached_claim, trex_deduct_advance_amount, change,
            (((absc_absent_to - add_months(absc_absent_from,trunc(months_between( absc_absent_to,absc_absent_from)))) * absc_allowance_per_night ) + trex_total_attached_claim) - trex_deduct_advance_amount   as total_payment,
            date_left , date_arrived, (date_arrived - add_months(date_left,trunc(months_between( date_arrived,date_left)))) as actual_days
            FROM eform_subsistence
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
        DB::statement("DROP VIEW subsistence_totals_view");
    }
}
