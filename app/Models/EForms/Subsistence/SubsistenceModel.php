<?php

namespace App\Models\EForms\Subsistence;

use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
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
        'type',

        'absc_absent_from',
        'absc_absent_to',
        'absc_visited_place',
        'absc_visited_reason',
        'absc_allowance_per_night',
        'absc_amount',

//        'trex_total_attached_claim',
//        'trex_total_claim_amount',
//        'trex_deduct_advance',
//        'trex_net_amount_paid',

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

        'hod_code',
        'hod_unit',
        'ca_code',
        'ca_unit',
        'hrm_code',
        'hrm_unit',
        'expenditure_code',
        'expenditure_unit',
        'dr_code',
        'dr_unit',
        'audit_code',
        'audit_unit',

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    //RELATIONSHIPS

    protected $with = [
        'user',
        'status',
    ];

    public function user_unit(){
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




    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();

            //[1]  GET YOUR PROFILE
            $profile_assignement = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.subsistence'))
                ->where('user_id', $user->id)->first();

            //  use my profile - if i dont have one - give me the default
            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->profile_unit_code = $user->user_unit_code;
            $user->profile_job_code = $user->job_code;
            $user->save();
//            dd($user);

            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
            $profile_delegated = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.subsistence'))
                ->where('delegated_to', $user->id)
                ->where('config_status_id',  config('constants.active_state') )->get();

            if (!$profile_delegated->isEmpty()) {
                //
                $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ?? $user->user_unit_code;
                $user->profile_job_code = $profile_delegated->first()->delegated_job_code ?? $user->job_code;
                $user->save();
            }


            //[1] REQUESTER
            //if you are just a requester, then only see your forms
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                static::addGlobalScope('staff_number', function (Builder $builder) {
                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
                });
            } else {
                //[2A] HOD
                //see forms for the same work area and user unit
                if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
//                      dd(Auth::user()->profile_job_code) ;
                    static::addGlobalScope('hod', function (Builder $builder) {
                        $builder->Where('hod_code', Auth::user()->profile_job_code);
                        $builder->where('hod_unit', Auth::user()->profile_unit_code);
                    });
                }
                //[2B] HUMAN RESOURCE
                //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
                    static::addGlobalScope('hrm', function (Builder $builder) {
                        $builder->Where('hrm_code', Auth::user()->profile_job_code);
                        $builder->where('hrm_unit', Auth::user()->profile_unit_code);
                    });
                }
                //[2C] CHIEF ACCOUNTANT
                //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
                    static::addGlobalScope('ca', function (Builder $builder) {
                        $builder->Where('ca_code', Auth::user()->profile_job_code);
                        $builder->where('ca_unit', Auth::user()->profile_unit_code);
                    });
                    //   dd(3);
                }
                //[2D] EXPENDITURE
                //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
                    static::addGlobalScope('expenditure', function (Builder $builder) {
                        //  $builder->Where('expenditure_code', Auth::user()->job_code);
                        $builder->where('expenditure_unit', Auth::user()->profile_unit_code);
                    });
                }

                //[2E] SECURITY
                //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
                    static::addGlobalScope('security', function (Builder $builder) {
                        // $builder->Where('security_code', Auth::user()->job_code);
                        $builder->where('security_unit', Auth::user()->profile_unit_code);
                    });
                }
                //[2F] AUDIT
                //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
                    static::addGlobalScope('audit', function (Builder $builder) {
                        // $builder->Where('security_code', Auth::user()->job_code);
                        $builder->where('audit_unit', Auth::user()->profile_unit_code);
                    });
                }
                else{

                }
            }

        }

    }



}
