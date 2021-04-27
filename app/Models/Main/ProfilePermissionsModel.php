<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Main\ProfileModel;
use App\Models\Main\EFormModel;

class ProfilePermissionsModel extends Model
{
    use HasFactory;
         use SoftDeletes;

    //table name
    protected $table  = 'config_profile_permission';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'eform_id',
        'profile',
        'profile_next',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

      protected $with = [
        'eform',
          'profiles',
          'profiles_next',
       ];

    public function eform(){
        return $this->belongsTo(EformModel::class, 'eform_id', 'id');
    }
    public function profiles(){
        return $this->belongsTo(ProfileModel::class, 'profile', 'code');
    }
    public function profiles_next(){
        return $this->belongsTo(ProfileModel::class, 'profile_next', 'code');
    }
    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
