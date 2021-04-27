<?php

namespace App\Models\EForms\PettyCash;

use App\Models\User;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'status_id',
        'account',
        'company',
        'intra_company',
        'project',
        'pems_project',
        'spare',
        'description',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $with = [
        'petty_cash',
        'status',
    ];

    public function petty_cash()
    {
        return $this->belongsTo(PettyCashModel::class, 'eform_petty_cash_id', 'id');
    }
    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

}
