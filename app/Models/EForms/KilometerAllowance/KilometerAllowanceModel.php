<?php

namespace App\Models\Eforms\KilometerAllowance;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Main\StatusModel;
use App\Models\User;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\EformApprovalsModel;

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
        'user_unit',
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

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',

        'hod_code',
        'hod_unit',

        'bm_code',
        'bm_unit',

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
    ];

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();
            $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('user_id', $user->id)->first();

            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;

            $user->save();

            //[1] REQUESTER
            //if you are just a requester, then only see your forms
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                static::addGlobalScope('staff_no', function (Builder $builder) {
                    $builder->where('staff_no', Auth::user()->staff_no);
                });
            } else {
                //[2A] HOD
                //see forms for the same work area and user unit
                if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {

                    //  dd(Auth::user()->user_unit->code) ;
                    static::addGlobalScope('user_unit_id', function (Builder $builder) {
                        $builder->where('user_unit', Auth::user()->user_unit->code);
                    });
                }
                //[2B] HUMAN RESOURCE
                //see forms for the same pay point
                if ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
//                    static::addGlobalScope('pay_point_id', function (Builder $builder) {
//                        $builder->where('pay_point_id', Auth::user()->pay_point_id);
//                    });
                }
                //[2C] CHIEF ACCOUNTANT
                //see forms for the same pay point
                if ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
//                    static::addGlobalScope('pay_point_id', function (Builder $builder) {
//                        $builder->where('pay_point_id', Auth::user()->pay_point_id);
//                    });
                }
            }

        }

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function claimant()
    {
        return $this->belongsTo(User::class, 'staff_no', 'staff_no');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }



}
