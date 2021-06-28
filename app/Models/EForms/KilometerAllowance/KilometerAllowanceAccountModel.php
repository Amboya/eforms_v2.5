<?php

namespace App\Models\EForms\KilometerAllowance;

use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class KilometerAllowanceAccountModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_kilometer_accounts';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'creditted_account_id',
        'creditted_amount',
        'debitted_account_id',
        'debitted_amount',
        'eform_kilometer_allowance_id',

        'kilometer_allowance_code',

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


}
