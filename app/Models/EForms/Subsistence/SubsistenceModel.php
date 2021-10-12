<?php

namespace App\Models\EForms\Subsistence;

use App\Http\Controllers\Main\HomeController;
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

    protected $appends = ['numdays', 'total'];

    protected $dates = [
        'absc_absent_to', 'absc_absent_from'
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
        'absc_amount',

        'trex_total_attached_claim',
        'trex_total_claim_amount',
        'trex_deduct_advance',
        'trex_net_amount_paid',

        'allocation_code',
        'total_amount',
        'total_days',

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
                static::addGlobalScope('hod', function (Builder $builder) {
                    $fdsf = HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
                    $mine = $fdsf->pluck('user_unit_code')->toArray();
                    $builder->WhereIn('user_unit_code', $mine);
                });

            }
        }
    }


    //RELATIONSHIPS

    public function getNumdaysAttribute()
    {
        return $this->absc_absent_from->diffInDays($this->absc_absent_to);
    }

    public function getTotalAttribute()
    {
        return $this->numdays * $this->absc_allowance_per_night;
    }

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


}
