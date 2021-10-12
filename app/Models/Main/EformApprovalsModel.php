<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Main\StatusModel;

class EformApprovalsModel extends Model
{
    use HasFactory;


    //table name
    protected $table = 'config_eform_approvals';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'profile',
        'staff_no',
        'claimant_staff_no',
        'name',
        'reason',
        'action',
        'current_status_id',
        'action_status_id',
        'config_eform_id',
        'eform_id',
        'eform_code',

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
