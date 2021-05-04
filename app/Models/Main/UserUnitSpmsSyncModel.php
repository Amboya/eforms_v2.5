<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnitSpmsSyncModel extends Model
{

    use HasFactory;
    //table name
    protected $table = 'config_work_flow';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'user_unit_id',
        'user_unit_code',
        'user_unit_description',
        'user_unit_superior',
        'user_unit_bc_code',
        'user_unit_cc_code',
        'user_unit_status',

        'dr_code',
        'dr_unit',

        'dm_code',
        'dm_unit',

        'hod_code',
        'hod_unit',

        'arm_code',
        'arm_unit',

        'bm_code',
        'bm_unit',

        'ca_code',
        'ca_unit',

        'ma_code',
        'ma_unit',

        'psa_code',
        'psa_unit',

        'hrm_code',
        'hrm_unit',

        'phro_code',
        'phro_unit',

        'shro_unit',
        'shro_code',

        'audit_code',
        'audit_unit',

        'expenditure_code',
        'expenditure_unit',

        'payroll_code',
        'payroll_unit',

        'security_code',
        'security_unit',

        'transport_code',
        'transport_unit',

        'created_at',
        'updated_at',

    ];


//    use HasFactory;
//
//    //table name
//    protected $table  = 'organizational_units';
//    //primary key
//    //   protected $primaryKey = 'user_unit';
//
//    //fields fillable
//    protected $fillable = [
//        'code_unit',
//        'description',
//        'bu_code',
//        'cc_code',
//        'code_unit_superior',
//        'status',
//    ] ;


}
