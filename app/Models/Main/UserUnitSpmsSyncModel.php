<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnitSpmsSyncModel extends Model
{

    use HasFactory;

    //table name
    protected $table  = 'organizational_units';

    //fields fillable
    protected $fillable = [
        'code_unit',
        'description',
        'bu_code',
        'cc_code',
        'code_unit_superior',
        'status',
    ] ;


}
