<?php

namespace App\Models\EForms\Subsistence\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsistenceTotalsView extends Model
{
    use HasFactory;

    //table name
    protected $table = 'subsistence_totals_view';
    //primary key
    protected $primaryKey = 'id';

    //table name
    protected $fillable = [
        'absc_absent_from',
        'absc_absent_to',
        'cost_center',
        'business_unit_code',
        'user_unit_code',
        'pay_point_id',
        'location_id',
        'division_id',
        'region_id',
        'directorate_id',
        'config_status_id',
        'section',
        'allocation_code',
        'claimant_staff_no',
        'created_at',
        'absc_visited_place',
        'claimant_unit_code',
        'claimant_name',
        'code',
        'trip_id',
        'days',
        'absc_allowance_per_night',
        'trex_total_attached_claim',
        'trex_deduct_advance_amount',
        'total_payment',
        'date_left',
        'date_arrived',
        'actual_day'
    ];

    //primary key
    protected $with = [
        'user',
        'status',
    ];


}
