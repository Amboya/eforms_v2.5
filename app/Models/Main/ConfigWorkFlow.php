<?php

namespace App\Models\Main;

use App\Models\User;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigWorkFlow extends Model
{
    use HasFactory;
    use Compoships;

    //table name
    protected $table = 'config_system_work_flow';
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

        'org_id',

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

        'sheq_code',
        'sheq_unit',

        'created_at',
        'updated_at',

    ];

    public function users_list()
    {
        return $this->hasMany(User::class, 'user_unit_code', 'user_unit_code');
    }

    public function users_list_unit_user()
    {
        return $this->hasMany(User::class, 'user_unit_code', 'user_unit_code');
    }

    public function operating()
    {
        return $this->belongsTo(OperatingUnits::class, 'user_unit_bc_code', 'bu_code');
    }


    //NON
    public function user_unit_code_user()
    {
        return $this->hasMany(User::class, ['job_code' ], ['hod_code' ])->where('con_st_code', 'ACT');
    }
    public function user_unit_code_delegate_user()
    {
        return $this->hasMany(User::class, ['job_code' ], ['hod_code' ])->where('con_st_code', 'ACT');
    }


    //HOD
    public function hod_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['hod_code', 'hod_unit'])->where('con_st_code', 'ACT');
    }
    public function hod_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['hod_code', 'hod_unit'])->where('con_st_code', 'ACT');
    }

    // 'director
    public function dr_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['dr_code', 'dr_unit'])->where('con_st_code', 'ACT');
    }

    public function dr_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['dr_code', 'dr_unit'])->where('con_st_code', 'ACT');
    }

    //        'divisional manager
    public function dm_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['dm_code', 'dm_unit']) ;
    }

    public function dm_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['dm_code', 'dm_unit']);
    }

    //    area manager
    public function arm_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['arm_code', 'arm_unit'])->where('con_st_code', 'ACT');
    }

    public function arm_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['arm_code', 'arm_unit'])->where('con_st_code', 'ACT');
    }

    //    branch manager
    public function bm_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['bm_code', 'bm_unit'])->where('con_st_code', 'ACT');
    }

    public function bm_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['bm_code', 'bm_unit'])->where('con_st_code', 'ACT');
    }

    //  chief accountant
    public function ca_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['ca_code', 'ca_unit'])->where('con_st_code', 'ACT');
    }

    public function ca_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['ca_code', 'ca_unit'])
            ->where('con_st_code', 'ACT');
    }

    //       management accountant
    public function ma_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['ma_code', 'ma_unit'])->where('con_st_code', 'ACT');
    }

    public function ma_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['ma_code', 'ma_unit'])->where('con_st_code', 'ACT');
    }

    //       psa accountant
    public function psa_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['psa_code', 'psa_unit'])->where('con_st_code', 'ACT');
    }

    public function psa_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['psa_code', 'psa_unit'])->where('con_st_code', 'ACT');
    }

    //       human resource management
    public function hrm_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['hrm_code', 'hrm_unit'])->where('con_st_code', 'ACT');
    }

    public function hrm_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['hrm_code', 'hrm_unit'])->where('con_st_code', 'ACT');
    }

    //       phro
    public function phro_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['phro_code', 'phro_unit'])->where('con_st_code', 'ACT');
    }

    public function phro_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['phro_code', 'phro_unit'])->where('con_st_code', 'ACT');
    }

    //       shro
    public function shro_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['shro_code', 'shro_unit'])->where('con_st_code', 'ACT');
    }

    public function shro_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['shro_code', 'shro_unit'])->where('con_st_code', 'ACT');
    }

    //       audit
    public function audit_unit_user()
    {
        return $this->hasMany(User::class, [ 'user_unit_code'], [ 'audit_unit'])->where('con_st_code', 'ACT')
            ->where('profile_id', config('constants.user_profiles.EZESCO_011')) ;
    }

    public function audit_unit_delegate_user()
    {
        return $this->hasMany(User::class, [ 'profile_unit_code'], [ 'audit_unit'])->where('con_st_code', 'ACT')
            ->where('profile_id_delegated', config('constants.user_profiles.EZESCO_011')) ;
    }

    //       expenditure
    public function expenditure_unit_user()
    {
        return $this->hasMany(User::class, [ 'user_unit_code'], [ 'expenditure_unit'])->where('con_st_code', 'ACT')
            ->where('profile_id', config('constants.user_profiles.EZESCO_014')) ;
    }

    public function expenditure_unit_delegate_user()
    {
        return $this->hasMany(User::class, [ 'profile_unit_code'], [ 'expenditure_unit'])->where('con_st_code', 'ACT')
            ->where('profile_id_delegated', config('constants.user_profiles.EZESCO_014'));
    }

    //       payroll
    public function payroll_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['payroll_code', 'payroll_unit'])->where('con_st_code', 'ACT');
    }

    public function payroll_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['payroll_code', 'payroll_unit'])->where('con_st_code', 'ACT');
    }

    //       security
    public function security_unit_user()
    {
        return $this->hasMany(User::class, [ 'user_unit_code'], [ 'security_unit'])->where('con_st_code', 'ACT')
            ->where('profile_id', config('constants.user_profiles.EZESCO_013')) ;
    }

    public function security_unit_delegate_user()
    {
        return $this->hasMany(User::class, [ 'profile_unit_code'], [ 'security_unit'])->where('con_st_code', 'ACT')
            ->where('profile_id_delegated', config('constants.user_profiles.EZESCO_013')) ;
    }

    //       transport
    public function transport_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['transport_code', 'transport_unit'])->where('con_st_code', 'ACT');
    }

    public function transport_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['transport_code', 'transport_unit'])->where('con_st_code', 'ACT');
    }

    //       sheq
    public function sheq_unit_user()
    {
        return $this->hasMany(User::class, ['job_code', 'user_unit_code'], ['sheq_code', 'sheq_unit'])->where('con_st_code', 'ACT');
    }

    public function sheq_unit_delegate_user()
    {
        return $this->hasMany(User::class, ['profile_job_code', 'profile_unit_code'], ['sheq_code', 'sheq_unit'])->where('con_st_code', 'ACT');
    }


}
