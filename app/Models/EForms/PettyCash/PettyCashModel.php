<?php

namespace App\Models\EForms\PettyCash;

use App\Http\Controllers\Main\HomeController;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
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

        'security_name',
        'security_staff_no',
        'security_date',

        'audit_office_name',
        'audit_office_staff_no',
        'audit_office_date',

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
                    $fdsf = HomeController::getMyProfile(config('constants.eforms_id.petty_cash'));
                    $mine = $fdsf->pluck('user_unit_code')->toArray();
                    $builder->WhereIn('user_unit_code', $mine);

                });
            }
        }
    }



//    protected static function booted()
//    {
//        //check if authenticated user
//        if (auth()->check()) {
//
////            //get the profile associated with petty cash, for this user
//            $user = Auth::user();
////            //[1]  GET YOUR PROFILE
////            $profile_assignement = ProfileAssigmentModel::
////            where('eform_id', config('constants.eforms_id.petty_cash'))
////                ->where('user_id', $user->id)->first();
////            //  use my profile - if i dont have one - give me the default
////            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
////            $user->profile_id = $default_profile;
////            $user->profile_unit_code = $user->user_unit_code;
////            $user->profile_job_code = $user->job_code;
////            $user->save();
////
////            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
////            $profile_delegated = ProfileDelegatedModel::
////            where('eform_id', config('constants.eforms_id.petty_cash'))
////                ->where('delegated_to', $user->id)
////                ->where('config_status_id', config('constants.active_state'));
////            if ($profile_delegated->exists()) {
////                //
////                $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');
////                $user->profile_id = $default_profile;
////                $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ?? $user->user_unit_code;
////                $user->profile_job_code = $profile_delegated->first()->delegated_job_code ?? $user->job_code;
////                $user->save();
////            }
//
////            [1] REQUESTER
////            if you are just a requester, then only see your forms
//            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
//                static::addGlobalScope('staff_number', function (Builder $builder) {
//                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
//                });
//            } else {
//
//                //see forms for the
//                if ($user->profile_id == config('constants.user_profiles.EZESCO_007')
//                    || $user->profile_id == config('constants.user_profiles.EZESCO_009')
//                    || $user->profile_id == config('constants.user_profiles.EZESCO_004')) {
//                    static::addGlobalScope('ca', function (Builder $builder) {
//                        $builder->Where(Auth::user()->code_column, Auth::user()->profile_job_code);
//                        $builder->where(Auth::user()->unit_column, Auth::user()->profile_unit_code);
//                    });
//
//                } //see forms for the
//                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')
//                    || $user->profile_id == config('constants.user_profiles.EZESCO_013')
//                    || $user->profile_id == config('constants.user_profiles.EZESCO_011')) {
//                    static::addGlobalScope('security', function (Builder $builder) {
//                        $builder->where(Auth::user()->unit_column, Auth::user()->profile_unit_code);
//                    });
//                }
//            }
//        }
//
//    }


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
