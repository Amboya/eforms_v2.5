<?php

namespace App\Models\EForms\PettyCash;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Main\StatusModel;

class PettyCashApprovalModel extends Model
{
    use HasFactory;

    //table name
    protected $table = 'eform_petty_cash_approval';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'profile',
        'staff_no',
        'title',
        'name',
        'reason',
        'action',
        'current_status_id',
        'action_status_id',
        'config_eform_id',
        'eform_petty_cash_id',

        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $with = [
        'from_status',
        'to_status',
    ];


    public function from_status()
    {
        return $this->belongsTo(StatusModel::class, 'current_status_id', 'id');
    }

    public function to_status()
    {
        return $this->belongsTo(StatusModel::class, 'action_status_id', 'id');
    }


}
