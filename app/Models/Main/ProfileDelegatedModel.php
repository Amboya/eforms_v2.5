<?php

namespace App\Models\Main;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProfileDelegatedModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'config_profile_delegated';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [

        'eform_id',
        'eform_code',
        'delegated_to',
        'delegated_user_unit',
        'delegated_job_code',
        'delegated_profile',
        'config_status_id',
        'delegation_end',

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $with = [

        'profile_details',
        'form',
    ];

    //RELATIONSHIP
    public function profile_details()
    {
        return $this->belongsTo(ProfileModel::class, 'delegated_profile', 'code');
    }

    public function form()
    {
        return $this->belongsTo(EFormModel::class, 'eform_id', 'id');
    }
}
