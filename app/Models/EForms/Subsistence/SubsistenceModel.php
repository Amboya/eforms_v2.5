<?php

namespace App\Models\EForms\Subsistence;

use App\Http\Controllers\Main\HomeController;
use App\Models\EForms\Trip\Destinations;
use App\Models\EForms\Trip\DestinationsApprovals;
use App\Models\EForms\Trip\Trip;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class SubsistenceModel extends Model
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
    ];
    protected $table = 'eform_subsistence';
    protected $primaryKey = 'id';

    //table name
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
        'code_superior',

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
        'account_number',

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
    ];
    //primary key
    protected $with = [
        'user',
        'status',
    ];


    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            $user = Auth::user();
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                //if you are just a requester, then only see your forms
                static::addGlobalScope('staff_number', function (Builder $builder) {
                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
                });
            } else {
                $fdsf = HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
                $mine = $fdsf->pluck('user_unit_code')->toArray();

                if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {

                    //get the list of trips am supposed to approve
                    $trips = Destinations::whereIn('user_unit_code' , $mine )->get();
                    $trip_ids = $trips->pluck('trip_id')->toArray();

                    static::addGlobalScope('hod', function (Builder $builder) use ($user, $mine , $trip_ids) {
                        $builder->where('claimant_staff_no', $user->staff_no)
                            ->orWhereIn('user_unit_code', $mine)
                            ->orWhereIn('trip_id', $trip_ids)
                        ;
                    });
                }
                else {
                    static::addGlobalScope('hod', function (Builder $builder) use ($user, $mine) {
                        $builder->where('claimant_staff_no', $user->staff_no)
                            ->orWhereIn('user_unit_code', $mine);
                    });
                }


            }
        }
    }


    //ATTRIBUTES
    public function getNumDaysAttribute()
    {
        return $this->absc_absent_from->diffInDays($this->absc_absent_to);
    }

    public function getActualDaysAttribute()
    {
        return ($this->date_to ?? $this->created_at)->diffInDays($this->date_arrived ?? $this->created_at);
    }

    public function getTotalNightAllowanceAttribute()
    {
        return ($this->num_days * $this->absc_allowance_per_night);
    }

    public function getDeductAdvanceAmountAttribute()
    {
        return ($this->actual_days * $this->absc_allowance_per_night);
    }

    public function getTotalClaimAmountAttribute()
    {
        return ($this->num_days * $this->absc_allowance_per_night) + $this->trex_total_attached_claim;
    }

    public function getNetAmountPaidAttribute()
    {
        return (($this->num_days * $this->absc_allowance_per_night) + $this->trex_total_attached_claim) - ($this->actual_days * $this->absc_allowance_per_night);
    }


    //RELATIONSHIPS

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_code', 'user_unit_code');
    }

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

    public function destinations()
    {
        return $this->hasMany(DestinationsApprovals::class, 'subsistence_id', 'id');
    }


}
