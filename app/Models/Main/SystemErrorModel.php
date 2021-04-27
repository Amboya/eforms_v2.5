<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemErrorModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_system_error';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'class',
        'function',
        'msg',
        'type',
        'user',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;
}
