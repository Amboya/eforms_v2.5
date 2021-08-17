<?php

namespace App\Models\EForms\Subsistence;

use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubsistenceAccountModel extends Model
{
    use HasFactory;
    use SoftDeletes ;

    //table name
    protected $table = 'eform_subsistence_accounts';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'creditted_account_id',
        'creditted_amount',
        'debitted_account_id',
        'debitted_amount',
        'eform_subsistence_id',

        'subsistence_code',

        'status_id',
        'account',
        'company',
        'intra_company',
        'project',
        'pems_project',
        'spare',
        'description',

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

    protected $with = [
        'status',
    ];

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();

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

}
