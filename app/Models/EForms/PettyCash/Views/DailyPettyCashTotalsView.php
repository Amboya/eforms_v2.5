<?php

namespace App\Models\EForms\PettyCash\Views;

use App\Models\EForms\PettyCash\PettyCashItemModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DailyPettyCashTotalsView extends Model
{
    use HasFactory;

    //table name
    protected $table = 'eform_petty_cash_dashboard_daily_totals_view';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'claim_date',
        'cost_center',
        'business_unit_code',
        'user_unit_code',
        'directorate_id',
        'total',
        'amount',
        'claimant_staff_no',
        'config_status_id',

        'hod_code',
        'hod_unit',
        'ca_code',
        'ca_unit',
        'hrm_code',
        'hrm_unit',
        'expenditure_code',
        'expenditure_unit',
        'security_code',
        'security_unit',
        'audit_code',
        'audit_unit',

    ];
    protected $with = [
        'status',
         'directorate'
    ];

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();

            //[1]  GET YOUR PROFILE
            $profile_assignement = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('user_id', $user->id)->first();
            //  use my profile - if i dont have one - give me the default
            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->profile_unit_code = $user->user_unit_code;
            $user->profile_job_code = $user->job_code;
            $user->save();

            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
            $profile_delegated = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('delegated_to', $user->id)
                ->where('config_status_id', config('constants.one'));
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
                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
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


    //RELATIONSHIP

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_code', 'user_unit_code');
    }

    public function directorate()
    {
        return $this->belongsTo(DirectoratesModel::class, 'directorate_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }


}
