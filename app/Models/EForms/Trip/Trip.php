<?php

namespace App\Models\EForms\Trip;

use App\Http\Controllers\Main\HomeController;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['numdays', 'total'];

    protected $dates = [
        'date_from', 'date_to'
    ];

    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to' => 'date:Y-m-d',
    ];

    protected $table = 'eform_trip';

    //table name
    protected $primaryKey = 'id';
    //primary key
    protected $fillable = [
        'date_from',
        'date_to',
        'hod_code',
        'hod_unit',

        'code',
        'name',
        'description',
        'destination',
        'config_status_id',
        'invited',

        'initiator_name',
        'initiator_staff_no',
        'initiator_date',

        'closed_by_name',
        'closed_by_staff_no',
        'closed_by_date',

        'created_by',
        'deleted_at',
    ];

    //fields fillable
    protected $with = [
        'status',
    ];


    //RELATIONSHIPS
    protected static function booted()
    {

        // Error Code : 1 Error Message : ORA-00001: unique constraint (ISDADMIN.USERS_EMAIL_UK) violated Position : 0 Statement : insert into "USERS" ("NAME", "NRC", "CONTRACT_TYPE", "CON_ST_CODE", "CON_WEF_DATE", "CON_WET_DATE", "JOB_CODE", "STAFF_NO", "EMAIL", "PHONE", "PASSWORD", "PROFILE_ID", "TYPE_ID", "PASSWORD_CHANGED", "GRADE_ID", "POSITIONS_ID", "LOCATION_ID", "USER_DIVISION_ID", "PAY_POINT_ID", "USER_DIRECTORATE_ID", "FUNCTIONAL_UNIT_ID", "USER_UNIT_ID", "USER_UNIT_CODE", "UPDATED_AT", "CREATED_AT") values (:p0, :p1, :p2, :p3, :p4, :p5, :p6, :p7, :p8, :p9, :p10, :p11, :p12, :p13, :p14, :p15, :p16, :p17, :p18, :p19, :p20, :p21, :p22, :p23, :p24) returning "ID" into :p25 Bindings : [PRISCILLA SWETA,303130/24/1,PERMANENT CONTRACT,ACT,2021-05-03 00:00:00,2053-03-29 00:00:00,CUSSO,20432,payroll-admin@zesco.co.zm,0954409060,$2y$10$URemKOzXLnKHVYOsxYWtgOUUHwKG8jIuTWeC/kksntxLkLwIZI8RO,1,3,1,85,1023,161,31,4,725,47,187,C1400,2021-09-27 10:21:11,2021-09-27 10:21:11,0]

        //check if authenticated user
        if (auth()->check()) {
            $user = Auth::user();
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                //if you are just a requester, then only see your forms
                static::addGlobalScope('staff_number', function (Builder $builder) {
//                    $fdsf = HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
//                    $mine = $fdsf->pluck('user_unit_code')->toArray();
//                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
                });
            } else {

//                dd(000000000);
                //see forms for the same work area and user unit
               static::addGlobalScope('hod', function (Builder $builder) {
//                        $fdsf = HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
//                        $mine = $fdsf->pluck('user_unit_code')->toArray();
//                        $builder->WhereIn('user_unit_code', $mine);
                    });

            }
        }
    }

    public function getNumdaysAttribute()
    {
        return $this->date_from->diffInDays($this->date_to);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_code', 'user_unit_code');
    }

    public function members()
    {
        return $this->hasMany(TripMembers::class, 'trip_id', 'id');
    }




}


