<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserUnitModel extends Model
{
    use HasFactory;

    //table name
    protected $table  = 'organizational_units';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code_unit',
        'bu_code',
        'cc_code',
        'code_unit_superior',

        'user_act',
        'date_act',
        'level',
        'post_level',

        'status'

    ] ;

}
