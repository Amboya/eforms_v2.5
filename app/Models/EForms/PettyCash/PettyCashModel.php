<?php

namespace App\Models\EForms\PettyCash;

use App\Models\Main\AttachedFileModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
use App\Models\Main\UserUnitModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PettyCashModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_petty_cash';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'cost_center',
        'business_unit_code',
        'user_unit_code',

        'pay_point_id',
        'location_id',
        'division_id',
        'region_id',
        'directorate_id',
        'projects_id',

        'total_payment',
        'change',
        'code',
        'ref_no',
        'claim_date',

        'profile',
        'code_superior',

        'claimant_name',
        'claimant_staff_no',
        'config_status_id',

        'AUTHORISED_BY',
        'AUTHORISED_STAFF_NO',
        'AUTHORISED_DATE',

        'STATION_MANAGER',
        'STATION_MANAGER_STAFF_NO',
        'STATION_MANAGER_DATE',

        'ACCOUNTANT',
        'ACCOUNTANT_STAFF_NO',
        'ACCOUNTANT_DATE',

        'EXPENDITURE_OFFICE',
        'EXPENDITURE_OFFICE_STAFF_NO',
        'EXPENDITURE_DATE',

        'SECURITY_NAME',
        'SECURITY_STAFF_NO',
        'SECURITY_DATE',

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

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'

    ];
    protected $with = [
        'user',
        'status',
        'project',
        'item',
       // 'accounts'
    ];

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();
            $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('user_id', $user->id)->first();
            //
            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->save();

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
                        $builder->Where('hod_code', Auth::user()->job_code);
                        $builder->where('hod_unit', Auth::user()->user_unit_code);
                    });
                }
                //[2B] HUMAN RESOURCE
                //see forms for the same pay point
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
                    static::addGlobalScope('hrm', function (Builder $builder) {
                        $builder->Where('hrm_code', Auth::user()->job_code);
                        $builder->where('hrm_unit', Auth::user()->user_unit_code);
                    });
                }
                //[2C] CHIEF ACCOUNTANT
                //see forms for the same pay point
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
                    static::addGlobalScope('ca', function (Builder $builder) {
                        $builder->Where('ca_code', Auth::user()->job_code);
                        $builder->where('ca_unit', Auth::user()->user_unit_code);
                    });
                 //   dd(3);
                }
                //[2D] EXPENDITURE
                //see forms for the same pay point
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
                    static::addGlobalScope('expenditure', function (Builder $builder) {
                      //  $builder->Where('expenditure_code', Auth::user()->job_code);
                        $builder->where('expenditure_unit', Auth::user()->user_unit_code);
                    });
                }

                //[2E] SECURITY
                //see forms for the same pay point
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
                    static::addGlobalScope('security', function (Builder $builder) {
                       // $builder->Where('security_code', Auth::user()->job_code);
                        $builder->where('security_unit', Auth::user()->user_unit_code);
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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function directorate()
    {
        return $this->belongsTo(DirectoratesModel::class, 'directorate_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(ProjectsModel::class, 'projects_id', 'id');
    }

    public function item()
    {
        return $this->hasMany(PettyCashItemModel::class, 'eform_petty_cash_id', 'id');
    }

}
