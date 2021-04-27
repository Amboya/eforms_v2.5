<?php

namespace App\Models;

use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DepartmentModel;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\DivisionsModel;
use App\Models\Main\GradesModel;
use App\Models\main\LocationModel;
use App\Models\main\PaypointModel;
use App\Models\main\FunctionalUnitModel;
use App\Models\Main\PositionModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\UserTypeModel;
use App\Models\Main\UserUnitModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'staff_no',
        'avatar',
        'phone',

        'nrc',
        'contract_type',
        'con_st_code',
        'con_wef_date',
        'con_wet_date',

        'total_login',
        'total_forms',
        'password_changed',

        'location_id',
        'pay_point_id',
        'functional_unit_id',

        'type_id',
        'grade_id',
        'profile_id',
        'user_unit_code',
        'job_code',
        'user_unit_id',
        'positions_id',
        'user_region_id',
        'user_division_id',
        'user_directorate_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    private $rules = array(
        'email' => 'required|unique:user',
        'staff_no'  => 'required',
        // .. more rules here ..

    );

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules);
        // return the result
        return $v->passes();
    }


    protected $with = [
        'user_unit',
        'user_profile',
        'delegated_profile',
        'user_type',
        'position',
    ];

    //RELATIONSHIP
    public function user_unit(){
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_id', 'id');
    }
    public function user_profile(){
        return $this->hasMany(ProfileAssigmentModel::class, 'user_id', 'id');
    }
    public function delegated_profile(){
        return $this->hasMany(ProfileDelegatedModel::class, 'delegated_to', 'id');
    }
    public function user_type(){
        return $this->belongsTo(UserTypeModel::class, 'type_id', 'id');
    }
    public function position(){
        return $this->belongsTo(PositionModel::class, 'positions_id', 'id');
    }
    public function directorate(){
        return $this->belongsTo(DirectoratesModel::class, 'user_directorate_id', 'id');
    }
    public function division(){
        return $this->belongsTo(DivisionsModel::class, 'user_division_id', 'id');
    }
    public function department(){
        return $this->belongsTo(FunctionalUnitModel::class, 'functional_unit_id', 'id');
    }
    public function grade(){
        return $this->belongsTo(GradesModel::class, 'grade_id', 'id');
    }
    public function pay_point(){
        return $this->belongsTo(PaypointModel::class, 'pay_point_id', 'id');
    }
    public function location(){
        return $this->belongsTo(LocationModel::class, 'location_id', 'id');
    }
}
