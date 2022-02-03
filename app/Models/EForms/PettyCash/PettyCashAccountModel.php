<?php

namespace App\Models\EForms\PettyCash;

use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PettyCashAccountModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_petty_cash_account';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'creditted_account_id',
        'creditted_amount',
        'debitted_account_id',
        'debitted_amount',
        'eform_petty_cash_id',

        'petty_cash_code',
        'vat_rate',
        'user_unit_code',
        'business_unit_code',
        'cost_center',
        'user_unit_code',

        'status_id',
        'account',
        'amount',
        'org_id',
        'company',
        'intra_company',
        'project',
        'pems_project',
        'spare',
        'description',
        'line_type',
        'account_type',

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
        'deleted_at',
    ];

    protected $with = [
        'status',
    ];

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();
            //if you are just a requester, then only see your forms
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                static::addGlobalScope('staff_number', function (Builder $builder) {
                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
                });
            } else {
                //see forms for the
                if ($user->profile_id == config('constants.user_profiles.EZESCO_007')
                    || $user->profile_id == config('constants.user_profiles.EZESCO_009')
                    || $user->profile_id == config('constants.user_profiles.EZESCO_004')) {
                    static::addGlobalScope('ca', function (Builder $builder) {
                        $builder->Where(Auth::user()->code_column, Auth::user()->profile_job_code);
                        $builder->where(Auth::user()->unit_column, Auth::user()->profile_unit_code);
                    });
                } //see forms for the
                elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')
                    || $user->profile_id == config('constants.user_profiles.EZESCO_013')
                    || $user->profile_id == config('constants.user_profiles.EZESCO_011')) {
                    static::addGlobalScope('security', function (Builder $builder) {
                        $builder->where(Auth::user()->unit_column, Auth::user()->profile_unit_code);
                    });
                }
            }
        }
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    public function form()
    {
        return $this->belongsTo(PettyCashModel::class, 'eform_petty_cash_id', 'id');
    }


}
