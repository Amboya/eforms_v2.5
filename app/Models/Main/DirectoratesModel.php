<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectoratesModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_directorate';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'user_unit_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

    protected $with = [
        'user',
        'user_unit',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function user_unit(){
        return $this->belongsTo(UserUnitModel::class, 'user_unit_id', 'id');
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_id', 'id');
    }

}
