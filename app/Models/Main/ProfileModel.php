<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_profile';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'description',
        'created_by',
        'created_at',
        'updated_at',
        'code_column',
        'unit_column',
        'deleted_at',
    ] ;
//    protected $with = [
//        'user',
//    ];
//    public function user(){
//        return $this->belongsTo(User::class, 'created_by', 'id');
//    }

}
