<?php

namespace App\Models\Eforms\KilometerAllowance;

use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class KilometerAllowanceModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_kilometer_allowance';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'code',
        'destination',
        'station',
        'purpose_of_visit',
        'start_date',
        'end_date',
        'reg_no',
        'engine_capacity',
        'fuel_type',
        'kilometers',
        'pump_price',
        'amount',
        'staff_name',
        'staff_no',
        'claim_date',
        'config_status_id',
        'profile',
        'user_unit_code',
        'cost_centre',
        'business_code',

        'authorised_by',
        'authorised_staff_no',
        'authorised_date',

        'station_manager',
        'station_manager_staff_no',
        'station_manager_date',

        'accountant',
        'accountant_staff_no',
        'accountant_date',

        'expenditure_office',
        'expenditure_office_staff_no',
        'expenditure_date',

        'hrm_manager',
        'hrm_manager_staff_no',
        'hrm_manager_date',

        'accountant',
        'accountant_staff_no',
        'accountant_date',

        'expenditure_office',
        'expenditure_office_staff_no',
        'expenditure_date',

        'change',

        'audit_office',
        'audit_staff_no',
        'audit_date',

        'security_office',
        'security_staff_no',
        'security_date',

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',

        'hod_code',
        'hod_unit',

        'dm_code',
        'dm_unit',

        'hrm_code',
        'hrm_unit',

        'ca_code',
        'ca_unit',

        'audit_code',
        'audit_unit',

        'expenditure_code',
        'expenditure_unit'

    ];
    protected $with = [
        'user',
        'status',
        'user_unit',
    ];

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();

            //[1]  GET YOUR PROFILE
            $profile_assignement = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('user_id', $user->id)->first();
            //  use my profile - if i dont have one - give me the default
            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->profile_unit_code = $user->user_unit_code;
            $user->profile_job_code = $user->job_code;
            $user->save();

            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
            $profile_delegated = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('delegated_to', $user->id)
                ->where('config_status_id', config('constants.active_state'));
            if ($profile_delegated->exists()) {
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
                    $builder->where('staff_no', Auth::user()->staff_no);
                });
            } else {
                //[2A] HOD
                //see forms for the same work area and user unit
                if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
                    //  dd(Auth::user()->user_unit->code) ;
                    static::addGlobalScope('hod', function (Builder $builder) {
                        $builder->Where('hod_code', Auth::user()->profile_job_code);
                        $builder->where('hod_unit', Auth::user()->profile_unit_code);
                    });
                }
                //[2B] SENIOR MANAGER
                //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_015')) {
                    static::addGlobalScope('hrm', function (Builder $builder) {
                        $builder->Where('dm_code', Auth::user()->profile_job_code);
                        $builder->where('dm_unit', Auth::user()->profile_unit_code);
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
                       //
                    });
                } else {


                }
            }

        }

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


}
