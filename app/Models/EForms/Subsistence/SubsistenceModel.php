<?php

namespace App\Models\EForms\Subsistence;

use App\Models\Main\EformApprovalsModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\StatusModel;
use App\Models\Main\UserUnitModel;
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

    protected $appends = ['numdays','total'];

    protected $dates = [
        'absc_absent_to', 'absc_absent_from'
    ];

    protected $casts = [
        'absc_absent_to' => 'date:Y-m-d',
        'absc_absent_from' => 'date:Y-m-d',
    ];

    public function getNumdaysAttribute(){
        return $this->absc_absent_from->diffInDays($this->absc_absent_to);
    }

    public function getTotalAttribute(){
        return $this->numdays * $this->absc_allowance_per_night;
    }

    //table name
    protected $table = 'eform_subsistence';
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

        'absc_absent_from',
        'absc_absent_to',
        'absc_visited_place_reason',
        'absc_visited_place',
        'absc_allowance_per_night',
        'absc_amount',

//        'trex_total_attached_claim',
//        'trex_total_claim_amount',
//        'trex_deduct_advance',
//        'trex_net_amount_paid',

        'allocation_code',
        'total_amount',

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

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    //RELATIONSHIPS

    protected $with = [
        'user',
        'status',
        'approval',
    ];

    public function user_unit(){
        return $this->belongsTo(UserUnitModel::class, 'user_unit_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }

    public function approval()
    {
        return $this->belongsTo(EformApprovalsModel::class, 'eform_id', 'id');
    }



    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();
            $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
                ->where('user_id', $user->id)->first();

            $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
            $user->profile_id = $default_profile ;

            $user->save();

            //[1] REQUESTER
            //if you are just a requester, then only see your forms
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002'))  {
                static::addGlobalScope('staff_number', function (Builder $builder) {
                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
                });
            } else {
                //[2A] HOD
                //see forms for the same work area and user unit
                if ($user->profile_id == config('constants.user_profiles.EZESCO_004'))  {
                    // dd(Auth::user()->user_unit->id) ;
                    static::addGlobalScope('user_unit_id', function (Builder $builder) {
                        $builder->where('user_unit_id', Auth::user()->user_unit->id );
                    });
                }
                //[2B] SNR MANAGER
                //see forms for where the code superior matches
                if ($user->profile_id == config('constants.user_profiles.EZESCO_015'))  {
                    // dd(Auth::user()->user_unit->id) ;
                    static::addGlobalScope('code_superior', function (Builder $builder) {
                        $builder->where('code_superior', Auth::user()->position->code );
                    });
                }
                //[2C] HUMAN RESOURCE
                //see forms for the same pay point
                if ($user->profile_id == config('constants.user_profiles.EZESCO_009'))  {
                    static::addGlobalScope('pay_point_id', function (Builder $builder) {
                        $builder->where('pay_point_id', Auth::user()->pay_point_id);
                    });
                }
                //[2D] CHIEF ACCOUNTANT
                //see forms for the same pay point
                if ($user->profile_id == config('constants.user_profiles.EZESCO_007'))  {
                    static::addGlobalScope('pay_point_id', function (Builder $builder) {
                        $builder->where('pay_point_id', Auth::user()->pay_point_id);
                    });
                }

                //[2E] AUDIT
                //see forms for the same pay point
                if ($user->profile_id == config('constants.user_profiles.EZESCO_011'))  {
                    static::addGlobalScope('pay_point_id', function (Builder $builder) {
                        $builder->where('pay_point_id', Auth::user()->pay_point_id);
                    });
                }
            }

        }

    }


}
