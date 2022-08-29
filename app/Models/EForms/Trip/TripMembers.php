<?php

namespace App\Models\EForms\Trip;

use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TripMembers extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['num_days', 'actual_days', 'total_night_allowance', 'net_amount_paid', 'total_claim_amount', 'deduct_advance_amount'];
    protected $dates = [
        'absc_absent_to', 'absc_absent_from', 'date_arrived', 'date_left'
    ];

    protected $casts = [
        'absc_absent_to' => 'date:Y-m-d',
        'absc_absent_from' => 'date:Y-m-d',
        'date_arrived' => 'date:Y-m-d',
        'date_left' => 'date:Y-m-d',
    ];


    //table name
    protected $table = 'view_trip_membership';
    //primary key
    protected $primaryKey = 'id';

    //fields fillable
    protected $fillable = [
        'cost_center',
        'business_unit_code',
        'user_unit_id',
        'user_unit_code',
        'pay_point_id',
        'location_id',
        'division_id',
        'region_id',
        'directorate_id',
        'config_status_id',
        'profile',
        'claimant_user_unit_code',

        'grade',
        'ext_no',
        'code',
        'ref_no',
        'claim_date',
        'claimant_name',
        'claimant_staff_no',
        'station',
        'section',
        'type',
        'trip_id',

        'absc_absent_from',
        'absc_absent_to',
        'absc_visited_place',
        'absc_visited_reason',
        'absc_allowance_per_night',

        'trex_total_attached_claim',
        'date_left',
        'date_arrived',
        'allocation_code',

        'authorised_by',
        'authorised_staff_no',
        'authorised_date',

        'station_manager',
        'station_manager_staff_no',
        'station_manager_date',

        'chief_accountant',
        'chief_accountant_staff_no',
        'chief_accountant_date',

        'hr_office',
        'hr_office_staff_no',
        'hr_date',

        'audit_name',
        'audit_staff_no',
        'audit_date',

        'expenditure_office',
        'expenditure_office_staff_no',
        'expenditure_date',

        'initiator_name',
        'initiator_staff_no',
        'initiator_date',

        'closed_by_name',
        'closed_by_staff_no',
        'closed_by_date',

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',

        'trip_code',
        'id as trip_id_a',
        'trip_date_to',
        'trip_date_from',
        'trip_hod_code',
        'trip_hod_unit',
        'trip_name',
        'trip_description',
        'trip_destination',
        'trip_status_id',
        'initiator_name',
        'initiator_staff_no',
        'initiator_date',
        'closed_by_name',
        'closed_by_staff_no',
        'closed_by_date'

    ];


    protected $with = [
        'status',
        'approvals'
    ];

    protected static function booted()
    {

    }



    //ATTRIBUTES
    public function getNumDaysAttribute()
    {
        return $this->absc_absent_from->diffInDays($this->absc_absent_to);
    }
    public function getActualDaysAttribute()
    {
        return ($this->date_to ?? $this->created_at)->diffInDays( $this->date_arrived ?? $this->created_at );
    }

    public function getTotalNightAllowanceAttribute()
    {
        return ($this->num_days * $this->absc_allowance_per_night ) ;
    }

    public function getDeductAdvanceAmountAttribute()
    {
        return ($this->actual_days * $this->absc_allowance_per_night ) ;
    }

    public function getTotalClaimAmountAttribute()
    {
        return ($this->num_days * $this->absc_allowance_per_night ) + $this->trex_total_attached_claim  ;
    }

    public function getNetAmountPaidAttribute()
    {
        return  ( ($this->num_days * $this->absc_allowance_per_night ) + $this->trex_total_attached_claim ) - ($this->actual_days * $this->absc_allowance_per_night )   ;
    }




    //RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(EformApprovalsModel::class, 'eform_code', 'trip_code');
    }

    public function destinations()
    {
        return $this->hasMany(DestinationsApprovals::class, 'subsistence_id', 'id');
    }

}

