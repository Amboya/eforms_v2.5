<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_department_user_unit';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'business_unit_code',
        'cost_center_code',
        'code_unit_superior_id',
        'code_unit_superior',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'status'

    ] ;

}
