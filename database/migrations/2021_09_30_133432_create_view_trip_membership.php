<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewTripMembership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
      CREATE OR REPLACE VIEW view_trip_membership AS
        (
        SELECT  b.* ,
        a.code as trip_code, a.id as trip_id_a , a.date_to as trip_date_to, a.date_from as trip_date_from, a.hod_code as trip_hod_code,
        a.hod_unit as trip_hod_unit, a.name as trip_name, a.description as trip_description, a.destination as trip_destination,
        a.config_status_id as trip_status_id
        FROM eform_trip a LEFT JOIN eform_subsistence b ON a.id = b.trip_id
        )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW view_trip_membership");
    }
}
