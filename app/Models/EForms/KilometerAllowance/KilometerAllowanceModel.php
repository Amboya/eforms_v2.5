<?php

namespace App\Models\Eforms\KilometerAllowance;

use App\Models\Main\ConfigWorkFlow;
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
        'user_unit',
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




}
