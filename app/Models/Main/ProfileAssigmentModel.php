<?php

namespace App\Models\Main;


use App\Models\Main\EFormModel;
use App\Models\Main\ProfileModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProfileAssigmentModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_profile_assignment';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'eform_id',
        'profile',
        'user_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

    protected $with = [

        'form',
    ];

    //RELATIONSHIP
    public function profiles(){
        return $this->belongsTo(ProfileModel::class, 'profile', 'code');
    }
    public function form(){
        return $this->belongsTo(EFormModel::class, 'eform_id', 'id');
    }


}
