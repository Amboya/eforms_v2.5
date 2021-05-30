<?php

namespace App\Models\Main;


use App\Models\User;
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
        'delegated_unit_column',
        'delegated_code_column',
        'config_status_id',
        'delegation_end',
        'reason',
        'created_by'
    ];

    protected $with = [
        'profile',
        'form'
    ];


    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'delegated_user_unit', 'user_unit_code');
    }

    public function me()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function delegation()
    {
        return $this->belongsTo(User::class, 'delegated_to', 'id');
    }

    public function profile()
    {
        return $this->belongsTo(ProfileModel::class, 'delegated_profile', 'id');
    }

    public function form()
    {
        return $this->belongsTo(EFormModel::class, 'eform_id', 'id');
    }

    //RELATIONSHIP
//    public function profile_details()
//    {
//        return $this->belongsTo(ProfileModel::class, 'delegated_profile', 'code');
//    }
//
//    public function form()
//    {
//        return $this->belongsTo(EFormModel::class, 'eform_id', 'id');
//    }

}
